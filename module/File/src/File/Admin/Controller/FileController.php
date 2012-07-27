<?php
namespace File\Admin\Controller;

use File\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class FileController extends RestfulModuleController
{
    protected $renders = array(
        'restPutFile' => 'blog/get',    
        'restPostFile' => 'upload/index',    
        'restDeleteFile' => 'remove/get',    
    );

    public function restIndexFile()
    {

    }

    public function restGetFile()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $postModel = Api::_()->getModel('File\Model\Post');
        $postinfo = $postModel->setItemParams($id)->getPost();
        return array(
            'post' => $postinfo,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restPostFile()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\UploadForm();
        $form->init()
             ->setData($postData)
             ->enableFilters()
             ->enableFileTransfer();

        if ($form->isValid()) {
            /*
            $fileTransfer = new \Zend\File\Transfer\Transfer();
            if($fileTransfer->receive()) {
                $files = $fileTransfer->getFileInfo();
                p($files);
            }
            */
            /*
            $postData = $form->getData();
            $postModel = Api::_()->getModel('File\Model\Post');
            $postData = $form->fieldsMap($postData, true);
            $postId = $postModel->setSubItemMap($subForms)->setItem($postData)->createPost();
            $this->redirect()->toUrl('/admin/blog/' . $postId);
            */

        } else {
            //p($form->getMessages());
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutFile()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\PostEditForm();
        $subForms = array(
            'Text' => array('File\Form\TextForm'),
        );
        $form->setSubforms($subForms)->init();

        $form->setData($postData)->enableFilters();

        $flashMesseger = array();
        if ($form->isValid()) {
            $postData = $form->getData();
            $postModel = Api::_()->getModel('File\Model\Post');
            $postData = $form->fieldsMap($postData, true);
            $postId = $postModel->setSubItemMap($subForms)->setItem($postData)->savePost();
            $this->redirect()->toUrl('/admin/blog/' . $postData['id']);
            $this->flashMessenger()->addMessage('post-edit-succeed');
        } else {
            //$this->flashMessenger()->addMessage('');
            $flashMesseger = array('post-edit-failed');
        }

        return array(
            'form' => $form,
            'post' => $postData,
            'flashMessenger' => $flashMesseger
        );
    }

    public function restDeleteFile()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\PostDeleteForm();
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $postTable = Api::_()->getDbTable('File\DbTable\Posts');

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
