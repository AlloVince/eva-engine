<?php
namespace Blog\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Blog\Model\PostTable,
    Eva\View\Model\ViewModel;

class PageController extends RestfulModuleController
{
    public function restGetPage()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');

        $postModel = Api::_()->getModel('Blog\Model\Post');
        $postinfo = $postModel->setItemParams($id)->getPost();
        return array(
            //'form' => $form,
            'post' => $postinfo,
        );
    }

}
