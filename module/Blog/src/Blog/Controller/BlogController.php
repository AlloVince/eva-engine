<?php
namespace Blog\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class BlogController extends RestfulModuleController
{
    protected $renders = array(
        'restPutBlog' => 'blank',    
        'restPostBlog' => 'blank',    
        'restDeleteBlog' => 'blank',    
    );

    public function restPostBlog()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\PostCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);
        
        $callback = $this->params()->fromPost('callback', '/pa/');
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $postId = $itemModel->setItem($postData)->createPost();
            $this->flashMessenger()->addMessage('post-create-succeed');
            $this->redirect()->toUrl($callback . $postId);

        } else {
            
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutBlog()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\PostEditForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $flashMesseger = array();

        $callback = $this->params()->fromPost('callback', '/feed/');
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $postId = $itemModel->setItem($postData)->savePost();

            $this->flashMessenger()->addMessage('post-edit-succeed');
            $this->redirect()->toUrl($callback . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteBlog()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\PostDeleteForm();
        $form->bind($postData);
        
        $callback = $this->params()->fromPost('callback', '/feed/');
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $itemModel->setItem($postData)->removePost();

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
