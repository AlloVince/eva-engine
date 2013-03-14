<?php
namespace Epic\Jobs;

use Eva\Api;
use Eva\Job\RelatedJobInterface;
use Core\JobManager;


class SendEmailByFriendApprove implements RelatedJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;

        $userId = $args['user_id'];
        $userEmail = $args['user_email'];
        $notificationId = $args['notification_id'];
        $notificationKey = $args['notificationKey'];
        $messageId = $args['message_id'];
        
        $config = Api::_()->getConfig();
        $args['domain'] = $config['queue']['domain']; 

        $mail = new \Core\Mail();
        $message = $mail->getMessage();
        $message->setTo($userEmail);
        
        $message->setSubject('Epicurissimo Friend Approve')
            ->setData($args)
            ->setTemplatePath(Api::_()->getModulePath('Epic') . '/view/')
            ->setTemplate('notification/' . strtolower($notificationKey) . '/email.phtml');
        $mail->send();
    }
}
