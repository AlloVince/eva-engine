<?php
    namespace Event\Controller;

    use Event\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

    class FeedController extends RestfulModuleController
    {
        protected $eventId;

        public function indexAction()
        {
            $postData = $this->params()->fromPost();
            $form = new \Activity\Form\MessageCreateForm();
            $form->useSubFormGroup()
            ->bind($postData);

            $callback = $this->params()->fromPost('callback', '/feed/');
            if ($form->isValid()) {
                $item = $form->getData();
                $itemModel = Api::_()->getModel('Activity\Model\Activity');

                if($postData['event_id']) {
                    $this->eventId = $postData['event_id'];
                    $eventManager = $this->getServiceLocator()->get('Application')->getEventManager();
                    $eventManager->attach('activity.model.activity.create.post', function($event) use ($itemModel){
                        $item = $itemModel->getItem();
                        $eventActivityItem = $itemModel->getItem('Event\Item\EventActivity');
                        $eventActivityItem->event_id = $this->eventId;
                        $eventActivityItem->message_id = $item->id;
                        $eventActivityItem->messageTime = \Eva\Date\Date::getNow();
                        $eventActivityItem->create();
                    });
                }
                $postId = $itemModel->setItem($item)->createActivity();
                $this->redirect()->toUrl($callback);

            } else {

            }

            $viewModel = new ViewModel(array(
                'form' => $form,
                'post' => $postData,
            ));
            $viewModel->setTemplate('blank');
            return $viewModel;
        }

        protected function onCreateActivity($event)
        {
        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $item = $itemModel->getItem();

        $eventActivityItem = $itemModel->getItem('Activity\Item\EventActivity');
        $eventActivityItem->event_id = $this->eventId;
        $eventActivityItem->message_id = $item->message_id;
        $eventActivityItem->messageTime = \Eva\Date\Date::getNow();

        $eventActivityItem->create();
    }
}
