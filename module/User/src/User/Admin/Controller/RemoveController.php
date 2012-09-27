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
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('User\Model\User');
        $item = $itemModel->getUser($id);
        return array(
            'callback' => $this->params()->fromQuery('callback'),
            'item' => $item,
        );
    }
}
