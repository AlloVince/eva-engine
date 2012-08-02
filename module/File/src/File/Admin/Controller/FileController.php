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
        $request = $this->getRequest();

        $query = $request->getQuery();

        $form = Api::_()->getForm('File\Form\FileSearchForm');
        $selectQuery = $form->fieldsMap($query, true);

        $fileModel = Api::_()->getModel('File\Model\File');
        $files = $fileModel->setItemListParams($selectQuery)->getFiles();
        $paginator = $fileModel->getPaginator();

        return array(
            'form' => $form,
            'files' => $files,
            'query' => $query,
            'paginator' => $paginator,
        );

    }

    public function restGetFile()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $fileModel = Api::_()->getModel('File\Model\File');
        $fileinfo = $fileModel->setItemParams($id)->getFile();
        return array(
            'file' => $fileinfo,
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

        $fileModel = Api::_()->getModel('File\Model\File');
        $flashMesseger = array();
        if ($form->isValid() && $form->getFileTransfer()->isUploaded()) {
            if($form->getFileTransfer()->receive()){
                $files = $form->getFileTransfer()->getFileInfo();
                $fileModel->setUploadFiles($files);
                $fileModel->createFiles();
                $lastFileId = $fileModel->getLastFileId();
                if($lastFileId) {
                    $this->flashMessenger()->addMessage('file-upload-succeed');
                    $this->redirect()->toUrl('/admin/file/' . $lastFileId);
                }
            }
        } else {
            //p($form->getFileTransfer()->getMessages());
            //p($form->getFileTransfer()->isUploaded());
            //p($form->getMessages());
            $flashMesseger = array('file-upload-failed');
        }

        return array(
            'form' => $form,
            'post' => $postData,
            'flashMessenger' => $flashMesseger
        );
    }

    public function restPutFile()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\PostEditForm();
        $form->init()
             ->setData($postData)
             ->enableFilters();

        $flashMesseger = array();
        if ($form->isValid()) {
            $postData = $form->getData();
            $fileModel = Api::_()->getModel('File\Model\File');
            $postData = $form->fieldsMap($postData, true);
            $postId = $fileModel->setItem($postData)->saveFile();
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
        $itemData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\FileDeleteForm();
        $form->enableFilters()->setData($itemData);
        if ($form->isValid()) {

            $itemData = $form->getData();
            $itemTable = Api::_()->getDbTable('File\DbTable\Files');
            $itemTable->where("id = {$itemData['id']}")->remove();

            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'post' => $itemData,
            );
        }
    }
}
