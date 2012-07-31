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
        $postModel = Api::_()->getModel('File\Model\File');
        $fileinfo = $postModel->setItemParams($id)->getFile();

        return array(
            'callback' => $this->getRequest()->getQuery()->get('callback'),
            'file' => $fileinfo,
        );
    }
}
