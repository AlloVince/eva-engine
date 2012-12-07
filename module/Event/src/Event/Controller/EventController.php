<?php
namespace Event\Controller;

use Event\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class EventController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexEvent' => 'blank',    
    );

    public function restIndexEvent()
    {
        $this->changeViewModel('json');
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
        $paginator = $paginator ? $paginator->toArray() : null;

        if(Api::_()->isModuleLoaded('User')){
            $userList = array();
            $userList = $itemModel->getUserList(array(
                'columns' => array(
                    'id',
                    'userName',
                    'email',
                ),
            ))->toArray(array(
                'self' => array(
                    'getEmailHash()',
                ),
            ));
            $items = $itemModel->combineList($items, $userList, 'User', array('user_id' => 'id'));
        }

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }
}
