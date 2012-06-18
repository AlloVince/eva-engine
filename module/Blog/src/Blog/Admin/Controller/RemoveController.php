<?php
namespace Blog\Admin\Controller;

use Blog\Form,
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
        $postTable = Api::_()->getDbTable('Blog\DbTable\Posts');
        $postinfo = $postTable->find($id);
        return array(
            'post' => $postinfo,
        );
    }
}
