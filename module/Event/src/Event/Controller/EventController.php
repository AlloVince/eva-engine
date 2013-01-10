<?php
namespace Event\Controller;

use Event\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

class EventController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexEvent' => 'blank',    
    );

    public function indexAction()
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
        $items = $items->toArray(array(
            'self' => array(
                '*'
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

        if (count($items) > 0) {
            foreach ($items as $key=>$item) {
                if (count($item['File']) > 0) {
                    unset($items[$key]['File'][0]);
                    $items[$key]['File'] = $item['File'][0];
                } else {
                    unset($items[$key]['File']);
                }
            }
        }

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
