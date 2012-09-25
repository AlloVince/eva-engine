<?php
namespace Engine\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Blog\Model\PostTable,
    Eva\View\Model\ViewModel;

class PagesController extends RestfulModuleController
{
    public function getAction()
    {
        $id = $this->params('id');
        $postModel = Api::_()->getModelService('Blog\Model\Post');
        $postinfo = $postModel->getPost($id);
        $this->pagecapture();
        return array(
            'post' => $postinfo,
        );
    }

}
