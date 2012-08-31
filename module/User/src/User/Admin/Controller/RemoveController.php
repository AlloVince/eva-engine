<?php
namespace User\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class RemoveController extends RestfulModuleController
{
    protected $renders = array(
    );

    public function restGetRemove()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $itemTable = Api::_()->getDbTable('User\DbTable\Users');
        $iteminfo = $itemTable->find($id);
        return array(
            'callback' => $this->getRequest()->getQuery()->get('callback'),
            'item' => $iteminfo,
        );
    }
}
