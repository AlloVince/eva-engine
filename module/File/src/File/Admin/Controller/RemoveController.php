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
        $id = $this->params('id');
        $itemModel = Api::_()->getModelService('File\Model\File');
        $item = $itemModel->getFile($id);
        return array(
            'item' => $item,
            'callback' => $this->params()->fromQuery('callback'),
        );
    }
}
