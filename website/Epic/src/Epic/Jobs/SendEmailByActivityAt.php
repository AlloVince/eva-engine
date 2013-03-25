<?php
namespace Epic\Jobs;

use Eva\Api;
use Eva\Job\RelatedJobInterface;
use Core\JobManager;


class SendEmailByActivityAt implements RelatedJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;

        $activityId = $args['id'];
        $userId = $args['user_id'];
        $atUserId = $args['at_user_id'];
        $atUserEmail = $args['at_user_email'];
        $notificationId = $args['notification_id'];
        $notificationKey = $args['notificationKey'];
        $activityId = $args['activity_id'];
        $messageId = $args['message_id'];

        $config = Api::_()->getConfig();
        $args['domain'] = $config['queue']['domain'];

        $mail = new \Core\Mail();
        $message = $mail->getMessage();
        $message->setTo($atUserEmail);
        
        $message->setSubject('Epicurissimo Notification')
            ->setData($args)
            ->setTemplatePath(Api::_()->getModulePath('Epic') . '/view/')
            ->setTemplate('notification/' . strtolower($notificationKey) . '/email.phtml');
        $mail->send();
    }
}
