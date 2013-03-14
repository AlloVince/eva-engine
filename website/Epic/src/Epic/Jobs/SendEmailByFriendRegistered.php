<?php
namespace Epic\Jobs;

use Eva\Api;
use Eva\Job\RelatedJobInterface;
use Core\JobManager;


class SendEmailByFriendRegistered implements RelatedJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;

        $inviteUserId = $args['inviteUserId'];
        $inviteUserEmail = $args['inviteUserEmail'];
        $notificationId = $args['notification_id'];
        $notificationKey = $args['notificationKey'];
        $messageId = $args['message_id'];
        
        $config = Api::_()->getConfig();
        $args['domain'] = $config['queue']['domain']; 

        $mail = new \Core\Mail();
        $message = $mail->getMessage();
        $message->setTo($inviteUserEmail);
        
        $message->setSubject('Epicurissimo Notification')
            ->setData($args)
            ->setTemplatePath(Api::_()->getModulePath('Epic') . '/view/')
            ->setTemplate('notification/' . strtolower($notificationKey) . '/email.phtml');
        $mail->send();
    }
}
