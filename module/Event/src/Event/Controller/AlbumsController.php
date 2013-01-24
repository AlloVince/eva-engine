<?php
    namespace Event\Controller;

    use Event\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

    class AlbumsController extends RestfulModuleController
    {
        protected $eventId;

        public function indexAction()
        {
            $request = $this->getRequest();
            $albumData = $request->getQuery();
            //$albumData = $this->params()->fromPost();
            $form = new \Epic\Form\AlbumCreateForm();
            $form->useSubFormGroup()
            ->bind($albumData);
            
            $callback = $this->params()->fromPost('callback', '/events/');
            if ($form->isValid()) {
                $item = $form->getData();
                $itemModel = Api::_()->getModel('Album\Model\Album');
            
                if($albumData['event_id']) {
                    $this->eventId = $albumData['event_id'];
                    $eventId = $this->eventId;
                    $eventManager = $this->getServiceLocator()->get('Application')->getEventManager();
                    $eventManager->attach('album.model.album.create.post', function($event) use ($itemModel, $eventId){
                        $item = $itemModel->getItem();
                        $eventAlbumItem = $itemModel->getItem('Event\Item\EventAlbum');
                        $eventAlbumItem->event_id = $eventId;
                        $eventAlbumItem->album_id = $item->id;
                        $eventAlbumItem->create();
                    });
                }
                $albumId = $itemModel->setItem($item)->createAlbum();
                $this->redirect()->toUrl($callback);

            } else {

            }

            $viewModel = new ViewModel(array(
                'form' => $form,
                'album' => $albumData,
            ));
            $viewModel->setTemplate('blank');
            return $viewModel;
        }

        protected function onCreateAlbum($event)
        {
            $itemModel = Api::_()->getModel('Album\Model\Album');
            $item = $itemModel->getItem();

            $eventAlbumItem = $itemModel->getItem('Event\Item\EventAlbum');
            $eventAlbumItem->event_id = $this->eventId;
            $eventAlbumItem->album_id = $item->id;
            $eventAlbumItem->create();
        }
    }
