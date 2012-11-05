<?php
namespace User\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

class FriendController extends RestfulModuleController
{
    public function indexAction()
    {
        $this->changeViewModel('json');
        $selectQuery = array(
            'from_user_id' => $this->params()->fromQuery('user_id', 1),
            'relationshiopStatus' => 'approved',
            'page' => $this->params()->fromQuery('page', 1),
            'rows' => $this->params()->fromQuery('rows', 16),
        );
        $itemModel = Api::_()->getModel('User\Model\Friend');
        $items = $itemModel->setItemList($selectQuery)->getFriendList()->toArray(array(
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
                    'join' => array(
                        'Profile' => array(
                            '*'
                        ),
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
