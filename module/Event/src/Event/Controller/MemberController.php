<?php
namespace Event\Controller;

use Event\Form,
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
            'event_id' => $this->params()->fromQuery('event_id'),
            'requestStatus' => 'active',
            'order' => $this->params()->fromQuery('order'),
            'page' => $this->params()->fromQuery('page', 1),
            'rows' => $this->params()->fromQuery('rows', 16),
        );
        $itemModel = Api::_()->getModel('Event\Model\EventUser');
        $items = $itemModel->setItemList($selectQuery)->getEventUserList()->toArray(array(
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
