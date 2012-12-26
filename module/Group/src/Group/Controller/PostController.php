<?php
    namespace Group\Controller;

    use Group\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

    class PostController extends RestfulModuleController
    {
        protected $groupId;

        public function indexAction()
        {
            $postData = $this->params()->fromPost();
            $form = new \Blog\Form\PostCreateForm();
            $form->useSubFormGroup()
            ->bind($postData);

            $callback = $this->params()->fromPost('callback', '/pages/');
            if ($form->isValid()) {
                $item = $form->getData();
                $itemModel = Api::_()->getModel('Blog\Model\Post');
                if($postData['group_id']) {
                    $this->groupId = $postData['group_id'];
                    $groupId = $this->groupId;
                    $eventManager = $this->getServiceLocator()->get('Application')->getEventManager();
                    $eventManager->attach('blog.model.post.create.post', function($event) use ($itemModel, $groupId){
                        $item = $itemModel->getItem();
                        $groupPostItem = $itemModel->getItem('Group\Item\GroupPost');
                        $groupPostItem->group_id = $groupId;
                        $groupPostItem->post_id = $item->id;
                        $groupPostItem->create();
                    });
                }
                $postId = $itemModel->setItem($item)->createPost();
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

        protected function onCreatePost($event)
        {
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $item = $itemModel->getItem();

            $groupPostItem = $itemModel->getItem('Group\Item\GroupPost');
            $groupPostItem->group_id = $this->groupId;
            $groupPostItem->post_id = $item->id;
            $groupPostItem->create();
        }
    }
