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
        $id = $this->params('id');
        $itemModel = Api::_()->getModelService('Blog\Model\Post');
        $item = $itemModel->getPost($id)->toArray();
        return array(
            'callback' => $this->params()->fromQuery('callback'),
            'item' => $item,
        );
    }
}
