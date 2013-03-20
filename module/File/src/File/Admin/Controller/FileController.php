<?php
namespace File\Admin\Controller;

use File\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class FileController extends RestfulModuleController
{
    protected $renders = array(
        'restPutFile' => 'file/get',    
        'restPostFile' => 'upload/index',    
        'restDeleteFile' => 'remove/get',    
    );

    public function restIndexFile()
    {
        $query = $this->getRequest()->getQuery();

        if (!$query['page']) {
            $query['page'] = 1;
        }

        $form = new Form\FileSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        }

        $itemModel = Api::_()->getModel('File\Model\File');
        $items = $itemModel->setItemList($query)->getFileList(array(
            'self' => array(
                '*',
                'getUrl()',
                'getThumb()',
                'getReadableFileSize()',
            ),
        ));
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetFile()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('File\Model\File');
        $item = $itemModel->getFile($id, array(
            'self' => array(
                '*',
                'getUrl()',
                'getThumb()',
                'getReadableFileSize()',
            ),
        ));
        return array(
            'item' => $item,
        );
    }

    public function restPostFile()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\UploadForm();
        $form->bind($postData);

        $itemModel = Api::_()->getModel('File\Model\File');
        if ($form->isValid() && $form->getFileTransfer()->isUploaded()) {
            if($form->getFileTransfer()->receive()){
                
                $files = $form->getFileTransfer()->getFileInfo();
                $itemModel->setUploadFiles($files);
                $itemModel->setConfigKey('default')->createFiles();

                $lastFileId = $itemModel->getLastFileId();

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
            'item' => $postData,
        );
    }

    public function restPutFile()
    {
        $postData = $this->params()->fromPost();

        $form = new Form\FileEditForm();
        $form->bind($postData);
        
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('File\Model\File');
            $itemModel->setItem($postData)->saveFile();

            $this->redirect()->toUrl('/admin/file/' . $postData['id']);
            $this->flashMessenger()->addMessage('file-edit-succeed');
        } else {
            //$this->flashMessenger()->addMessage('');
            $flashMesseger = array('post-edit-failed');
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restDeleteFile()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\FileDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('File\Model\File');
            $itemId = $itemModel->setItem($postData)->removeFile();
            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'item' => $postData,
            );
        }
    }
}
