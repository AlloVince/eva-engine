<?php
namespace File\Admin\Controller;

use File\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class RemoveController extends RestfulModuleController
{
    protected $renders = array(
    );

    public function restGetRemove()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $postTable = Api::_()->getDbTable('File\DbTable\Posts');
        $postinfo = $postTable->find($id);
        return array(
            'callback' => $this->getRequest()->getQuery()->get('callback'),
            'post' => $postinfo,
        );
    }
}
