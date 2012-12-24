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
    );

    public function restGetCommentCreate()
    {
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

    public function restPostBlog()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\PostCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Post');

            $user = \Core\Auth::getLoginUser('Auth_Admin');
            $postData['user_id'] = $user['id'];
            $postData['user_name'] = $user['userName'];
            $postId = $itemModel->setItem($postData)->createPost();
            $this->flashMessenger()->addMessage('post-create-succeed');
            $this->redirect()->toUrl('/admin/blog/' . $postId);

        } else {
            p($postData);
            p($form->getMessages());
            //p($form->getElements(), 1);
            foreach($form->getFieldsets() as $fieldset){
             //   p($fieldset->getMessages());
            //    p($fieldset->getElements());
            }
            
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

    public function restDeleteBlog()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\PostDeleteForm();
        $form->bind($postData);
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
