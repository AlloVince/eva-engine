<?php
namespace Epic\Jobs;

use Eva\Api;
use Eva\Job\RelatedJobInterface;
use Core\JobManager;


class NotificateAtUsers implements RelatedJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;

        $activityId = $args['id'];
        $userId = $args['user_id'];
        $userNames = $args['userNames'];
        $authorName = $args['authorName'];
        $userModel = Api::_()->getModel('User\Model\User');
        $userIdArray = array();
        $users = array();
        foreach($userNames as $userName){
            $user = clone $userModel->getUser($userName);
            if(!$user) {
                continue;
            }
            $users[] = $user;
            $userIdArray[] = $user['id'];
        }

        if(!$users) {
            return;
        }

        $notificationKey = 'ActivityAt';
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

        foreach($users as $user){
            //Not notice user self
            if($user->id == $userId){
                continue;
            }

            $notificationModel->setUser($user)
                ->setNotification($notificationItem);

            $notificationSetting = $notificationModel->getUserSetting();

            if($notificationSetting['sendNotice']){
                JobManager::setQueue('sendnotice');
                JobManager::jobHandler('Epic\Jobs\SendNoticeByActivityAt', array(
                    'notification_id' => $notificationId,
                    'notificationKey' => $notificationKey,
                    'id' => $activityId,
                    'user_id' => $userId,
                    'authorName' => $authorName,
                    'at_user_id' => $user->id,
                    'activity_id' => $activityId,
                    'message_id' => $messageItem->id,
                ));
            }

            if($notificationSetting['sendEmail']){
                JobManager::setQueue('sendmail');
                JobManager::jobHandler('Epic\Jobs\SendEmailByActivityAt', array(
                    'notification_id' => $notificationId,
                    'notificationKey' => $notificationKey,
                    'id' => $activityId,
                    'user_id' => $userId,
                    'authorName' => $authorName,
                    'at_user_id' => $user->id,
                    'at_user_email' => $user->email,
                    'activity_id' => $activityId,
                    'message_id' => $messageItem->id,
                ));
            }

        }
    }
}
