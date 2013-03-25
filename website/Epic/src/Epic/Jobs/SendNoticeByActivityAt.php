<?php
namespace Epic\Jobs;

use Eva\Api;
use Eva\Job\RelatedJobInterface;
use Core\JobManager;
use Eva\View\Model\ViewModel;


class SendNoticeByActivityAt implements RelatedJobInterface
{
    public $args;

    public function perform()
    {
        $args = $this->args;

        $activityId = $args['id'];
        $userId = $args['user_id'];
        $atUserId = $args['at_user_id'];
        $notificationId = $args['notification_id'];
        $notificationKey = $args['notificationKey'];
        $activityId = $args['activity_id'];
        $messageId = $args['message_id'];

        $noticeModel = Api::_()->getModel('Notification\Model\Notice');
        
        $noticeModel->createNotice(array(
            'user_id' => $atUserId,
            'message_id' => $messageId,
            'notification_id' => $notificationId,
            'notificationKey' => $notificationKey,
            'createTime' => \Eva\Date\Date::getNow(),
            'content' => $this->getContent($args),
        ));


        //TODO:Insert into messages_users table
    }

    public function getContent($data)
    {
        $view = new \Zend\View\Renderer\PhpRenderer();
        $resolver = new \Zend\View\Resolver\TemplateMapResolver();
        $resolver->setMap(array(
            'mailTemplate' => __DIR__ . '/../../../view/notification/' . strtolower($data['notificationKey']) . '/notice.phtml'
        ));
        $view->setResolver($resolver);
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('mailTemplate')
            ->setVariables($data);
        
        return $view->render($viewModel);
    }
}
