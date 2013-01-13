<?php
namespace Blog\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

class CommentController extends RestfulModuleController
{
    protected $renders = array(
        'restPutComment' => 'blank',    
        'restPostComment' => 'blank',    
        'restDeleteComment' => 'blank',    
    );

    public function restPostComment()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\CommentCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);
        
        $callback = $this->params()->fromPost('callback');
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Comment');
            $postId = $itemModel->setItem($postData)->createComment();
            $this->redirect()->toUrl($callback);
        } else {
        }
        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutComment()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\CommentEditForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $callback = $this->params()->fromPost('callback');
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Comment');
            $postId = $itemModel->setItem($postData)->savePost();
            $this->redirect()->toUrl($callback . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteComment()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\CommentDeleteForm();
        $form->bind($postData);
        
        $callback = $this->params()->fromPost('callback');
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Comment');
            $itemModel->setItem($postData)->removeComment();

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
