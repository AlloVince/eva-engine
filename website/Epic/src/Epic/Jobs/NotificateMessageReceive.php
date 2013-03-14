<?php
namespace Epic\Jobs;

use Eva\Api;
use Eva\Job\RelatedJobInterface;
use Core\JobManager;


class NotificateMessageReceive implements RelatedJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;

        $sender = $args['sender'];
        $recipientId = $args['recipientId'];
        $body = $args['body'];
        
        $userModel = \Eva\Api::_()->getModel('User\Model\User');
        $user = $userModel->getUser($recipientId);

        if(!$user || !$sender){
            return;
        }

        $notificationKey = 'MessageReceive';
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
        
        $user = $user->toArray();

        if($notificationSetting['sendNotice']){
            JobManager::setQueue('sendnotice');
            JobManager::jobHandler('Epic\Jobs\SendNoticeByMessageReceive', array(
                'notification_id' => $notificationId,
                'notificationKey' => $notificationKey,
                'message_id' => $messageItem->id,
                'body' => $body,
                'sender' => $sender,
                'recipient' => $user,
            ));
        }

        if($notificationSetting['sendEmail']){
            JobManager::setQueue('sendmail');
            JobManager::jobHandler('Epic\Jobs\SendEmailByMessageReceive', array(
                'notification_id' => $notificationId,
                'notificationKey' => $notificationKey,
                'message_id' => $messageItem->id,
                'body' => $body,
                'sender' => $sender,
                'recipient' => $user,
            ));
        }
    }
}
