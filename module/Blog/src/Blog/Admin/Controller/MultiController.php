<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class MultiController extends RestfulModuleController
{
    protected $renders = array(
    );

    public function restPutMulti()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\PostForm();
        $subForms = array(
            'Text' => array('Blog\Form\TextForm'),
        );
        $form->setSubforms($subForms)->init();

        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $postModel = Api::_()->getModel('Blog\Model\Post');
            $postData = $form->fieldsMap($postData, true);
            $postId = $postModel->setSubItemMap($subForms)->setItem($postData)->savePost();
            $this->redirect()->toUrl('/admin/blog/' . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restDeleteBlog()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\PostDeleteForm();
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $postTable = Api::_()->getDbTable('Blog\DbTable\Posts');

            $postTable->where("id = {$postData['id']}")->remove();

            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'post' => $postData,
            );
        }
    }
}
