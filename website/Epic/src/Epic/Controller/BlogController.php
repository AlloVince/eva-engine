<?php
namespace Epic\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;
use Core\Auth;
use Blog\Form;

class BlogController extends ActionController
{

    public function removeAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData = $this->params()->fromPost();
            $callback = $this->params()->fromPost('callback');

            $form = new Form\PostDeleteForm();
            $form->bind($postData);
            if ($form->isValid()) {

                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Blog\Model\Post');
                $itemModel->setItem($postData)->removePost();
                $callback = $callback ? $callback : '/my/blog/';
                $this->redirect()->toUrl($callback);

            } else {
                return array(
                    'post' => $postData,
                );
            }

        } else {
            $id = $this->params('id');
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $item = $itemModel->getPost($id)->toArray();
            return array(
                'callback' => $this->params()->fromQuery('callback'),
                'item' => $item,
            );

        }

    }

    public function createAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return;
        }

        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');
        $form = new Form\PostCreateForm();
        $form->useSubFormGroup()
        ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $postId = $itemModel->setItem($postData)->createPost();
            $callback = $callback ? $callback : '/my/blog/';
            $this->redirect()->toUrl($callback);
        } else {

        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/blog/create');
        if ($request->isPost()) {
            $postData = $this->params()->fromPost();
            $callback = $this->params()->fromPost('callback');
            $form = new Form\PostEditForm();
            $form->useSubFormGroup()
            ->bind($postData);

            if ($form->isValid()) {
                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Blog\Model\Post');
                $postId = $itemModel->setItem($postData)->savePost();
                $callback = $callback ? $callback : '/my/blog/';
                $this->redirect()->toUrl($callback);

            } else {
            }

            $viewModel->setVariables(array(
                'form' => $form,
                'item' => $postData,
            ));
        } else {
            $id = $this->params('id');
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $item = $itemModel->getPost($id, array(
                'self' => array(
                    '*',
                ),
                'join' => array(
                    'Text' => array(
                        'self' => array(
                            '*',
                            'getContentHtml()',
                        ),
                    ),
                    'Categories' => array(
                    ),
                ),
                'proxy' => array(
                    'File\Item\File::PostCover' => array(
                        'self' => array(
                            '*',
                            'getThumb()',
                        )
                    )
                ),
            ));
            if(isset($item['FileConnect'][0])){
                $item['FileConnect'] = $item['FileConnect'][0];
            }

            $viewModel->setVariables(array(
                'item' => $item,
            ));
        }

        return $viewModel;
    }
}
