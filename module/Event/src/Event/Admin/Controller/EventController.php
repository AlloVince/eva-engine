<?php
namespace Event\Admin\Controller;

use Event\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class EventController extends RestfulModuleController
{
    protected $renders = array(
        'restPutEvent' => 'event/get',    
        'restPostEvent' => 'event/get',    
        'restDeleteEvent' => 'remove/get',    
    );

    public function restIndexEvent()
    {
        $query = $this->getRequest()->getQuery();
        $form = new Form\EventSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $itemModel = Api::_()->getModel('Event\Model\Event');
        $items = $itemModel->setItemList($query)->getEventdataList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetEvent()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Event\Model\Event');
        $item = $itemModel->getEventdata($id, array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                    ),
                ),
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
            ),
        ));

        if(isset($item['EventFile'][0])){
            $item['EventFile'] = $item['EventFile'][0];
        }

        return array(
            'item' => $item,
        );
    }

    public function restPostEvent()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\EventCreateForm();
        $form->useSubFormGroup()
            ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Event\Model\Event');
            $eventId = $itemModel->setItem($postData)->createEventdata();
            $this->flashMessenger()->addMessage('event-create-succeed');
            $this->redirect()->toUrl('/admin/event/' . $eventId);

        } else {

        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutEvent()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\EventEditForm();
        $form->useSubFormGroup()
            ->bind($postData);

        $flashMesseger = array();

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Event\Model\Event');
            $eventId = $itemModel->setItem($postData)->saveEventdata();

            $this->flashMessenger()->addMessage('event-edit-succeed');
            $this->redirect()->toUrl('/admin/event/' . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteEvent()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\EventDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Event\Model\Event');
            $itemModel->setItem($postData)->removeEventdata();

            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'post' => $postData,
            );
        }
    }
}
