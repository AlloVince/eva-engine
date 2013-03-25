<?php
namespace Epic\Jobs;

use Eva\Api;
use Eva\Job\RelatedJobInterface;
use Core\JobManager;


class NotificateFriendRegistered implements RelatedJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;

        $userId = $args['user_id'];
        $userName = $args['user_name'];
        $inviteUserId = $args['inviteUserId'];
        $inviteUserName = $args['inviteUserName'];
        
        $userModel = Api::_()->getModel('User\Model\User');
        $user = clone $userModel->getUser($inviteUserName);
        if(!$user) {
            return;
        }

        $notificationKey = 'FriendRegistered';
        $notificationModel = Api::_()->getModel('Notification\Model\Notification');
        $notificationItem = $notificationModel->getNotification($notificationKey);
        $notificationId = $notificationItem->id;
        
        $messageModel = Api::_()->getModel('Notification\Model\Message');
        $messageModel->createMessage(array(
            'notification_id' => $notificationId,
            'notificationKey' => $notificationKey,
            'args' => \Zend\Json\Json::encode($args),
            'createTime' => \Eva\Date\Date::getNow(),
        ));
        $messageItem = $messageModel->getItem();

        $notificationModel->setUser($user)
                ->setNotification($notificationItem);
        $notificationSetting = $notificationModel->getUserSetting();

        if($notificationSetting['sendNotice']){
            JobManager::setQueue('sendnotice');
            JobManager::jobHandler('Epic\Jobs\SendNoticeByFriendRegistered', array(
                'notification_id' => $notificationId,
                'notificationKey' => $notificationKey,
                'user_name' => $userName,
                'user_id' => $userId,
                'inviteUserId' => $inviteUserId,
                'inviteUserName' => $inviteUserName,
                'message_id' => $messageItem->id,
            ));
        }

        if($notificationSetting['sendEmail']){
            JobManager::setQueue('sendmail');
            JobManager::jobHandler('Epic\Jobs\SendEmailByFriendRegistered', array(
                'notification_id' => $notificationId,
                'notificationKey' => $notificationKey,
                'user_name' => $userName,
                'user_id' => $userId,
                'inviteUserId' => $inviteUserId,
                'inviteUserName' => $inviteUserName,
                'inviteUserEmail' => $user->email,
                'message_id' => $messageItem->id,
            ));
        }
    }
}
