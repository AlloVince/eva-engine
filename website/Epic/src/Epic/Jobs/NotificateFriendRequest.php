<?php
namespace Epic\Jobs;

use Eva\Api;
use Eva\Job\RelatedJobInterface;
use Core\JobManager;


class NotificateFriendRequest implements RelatedJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;

        $userId = $args['user_id'];
        $friendId = $args['friend_id'];
        
        $userModel = Api::_()->getModel('User\Model\User');
        $user = clone $userModel->getItem();
        $friend = clone $userModel->getItem();

        $user->id = $userId;
        $user->self(array('*'));
        
        $friend->id = $friendId;
        $friend->self(array('*'));

        if(!$user) {
            return;
        }

        $notificationKey = 'FriendRequest';
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
            JobManager::jobHandler('Epic\Jobs\SendNoticeByFriendRequest', array(
                'notification_id' => $notificationId,
                'notificationKey' => $notificationKey,
                'user_id' => $userId,
                'user_name' => $user->userName,
                'friend_id' => $friendId,
                'friend_name' => $friend->userName,
                'message_id' => $messageItem->id,
            ));
        }

        if($notificationSetting['sendEmail']){
            JobManager::setQueue('sendmail');
            JobManager::jobHandler('Epic\Jobs\SendEmailByFriendRequest', array(
                'notification_id' => $notificationId,
                'notificationKey' => $notificationKey,
                'user_id' => $userId,
                'user_name' => $user->userName,
                'user_email' => $user->email,
                'friend_id' => $friendId,
                'friend_name' => $friend->userName,
                'message_id' => $messageItem->id,
            ));
        }
    }
}
