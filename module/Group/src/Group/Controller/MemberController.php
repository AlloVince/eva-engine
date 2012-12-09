<?php
namespace Group\Controller;

use Group\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

class MemberController extends RestfulModuleController
{
    public function indexAction()
    {
        $this->changeViewModel('json');
        $selectQuery = array(
            'group_id' => $this->params()->fromQuery('group_id'),
            'requestStatus' => 'active',
            'page' => $this->params()->fromQuery('page', 1),
            'rows' => $this->params()->fromQuery('rows', 16),
        );
        $itemModel = Api::_()->getModel('Group\Model\GroupUser');
        $items = $itemModel->setItemList($selectQuery)->getGroupUserList()->toArray(array(
            'self' => array(
            ),
            'join' => array(
                'User' => array(
                    'self' => array(
                        'id',
                        'userName',
                        'email',
                        'getEmailHash()',
                    ), 
                ),
            ),
        ));

        $paginator = $itemModel->getPaginator();
        $paginator = $paginator ? $paginator->toArray() : null;

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }
}
