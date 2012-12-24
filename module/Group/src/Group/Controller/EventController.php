<?php
    namespace Group\Controller;

    use Group\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

    class EventController extends RestfulModuleController
    {
        protected $groupId;

        public function indexAction()
        {
            $postData = $this->params()->fromPost();
            $form = new \Event\Form\EventCreateForm();
            $form->useSubFormGroup()
            ->bind($postData);

            $callback = $this->params()->fromPost('callback', '/events/');
            if ($form->isValid()) {
                $item = $form->getData();
                $itemModel = Api::_()->getModel('Event\Model\Event');

                if($postData['group_id']) {
                    $this->groupId = $postData['group_id'];
                    $eventManager = $this->getServiceLocator()->get('Application')->getEventManager();
                    $eventManager->attach('event.model.event.create.post', function($event) use ($itemModel){
                        $item = $itemModel->getItem();
                        $groupEventItem = $itemModel->getItem('Group\Item\GroupEvent');
                        $groupEventItem->group_id = $this->groupId;
                        $groupEventItem->event_id = $item->id;
                        $groupEventItem->create();
                    });
                }
                $postId = $itemModel->setItem($item)->createEventdata();
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

        protected function onCreateEvent($event)
        {
            $itemModel = Api::_()->getModel('Event\Model\Event');
            $item = $itemModel->getItem();

            $groupEventItem = $itemModel->getItem('Group\Item\GroupEvent');
            $groupEventItem->group_id = $this->groupId;
            $groupEventItem->event_id = $item->id;
            $groupEventItem->create();
        }
    }
