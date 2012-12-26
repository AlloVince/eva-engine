<?php
namespace Group\Controller;

use Group\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

class GroupController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexGroup' => 'blank',    
    );

    public function indexAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $form = new Form\GroupSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $itemModel = Api::_()->getModel('Group\Model\Group');
        $items = $itemModel->setItemList($query)->getGroupList();
        $items = $items->toArray(array(
            'self' => array(
            ),
            'join' => array(
                'Count' => array(
                    '*',
                ),
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
            ), 
        ));

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
