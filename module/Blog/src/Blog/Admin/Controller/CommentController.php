<?php
namespace Blog\Admin\Controller;

use Blog\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class CommentController extends RestfulModuleController
{
    protected $addResources = array(
        'create',
        'remove'
    );

    protected $renders = array(
        'restGetCommentCreate' => 'comment/get',    
        'restPutComment' => 'comment/get',    
        'restPostComment' => 'comment/get',    
        'restDeleteComment' => 'comment/remove',    
    );

    public function restGetCommentCreate()
    {
        $item = $this->getRequest()->getQuery();

        return array(
            'item' => $item
        );
    }

    public function restGetCommentRemove()
    {
        $item = array(
            'id' => $this->params('id')
        );

        return array(
            'item' => $item
        );
    }

    public function restIndexComment()
    {
        $query = $this->getRequest()->getQuery();
        $form = new Form\CommentSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $itemModel = Api::_()->getModel('Blog\Model\Comment');
        $items = $itemModel->setItemList($query)->getCommentList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetComment()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Blog\Model\Comment');
        $item = $itemModel->getComment($id, array(
            'self' => array(
                '*',
                'getContentHtml()',
            ),
        ));

        return array(
            'item' => $item,
        );
    }

    public function restPostComment()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\CommentCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Comment');

            $user = \Core\Auth::getLoginUser('Auth_Admin');
            if(!$postData['user_id']){
                $postData['user_id'] = $user['id'];
            }
            if(!$postData['user_name']){
                $postData['user_name'] = $user['userName'];
            }
            $itemId = $itemModel->setItem($postData)->createComment();
            $this->redirect()->toUrl('/admin/blog/comment/' . $itemId);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restPutComment()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\CommentEditForm();
        $form->useSubFormGroup()
             ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Comment');
            $postId = $itemModel->setItem($postData)->saveComment();
            $this->redirect()->toUrl('/admin/blog/comment/' . $postData['id']);

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

        $form = new Form\PostDeleteForm();
        $form->bind($postData);
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
