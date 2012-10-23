<?php
namespace Video\Admin\Controller;

use Video\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class VideoController extends RestfulModuleController
{
    protected $renders = array(
        'restPutVideo' => 'activity/get',    
        'restPostVideo' => 'activity/get',    
        'restDeleteVideo' => 'activity/delete',    
    );

    public function restIndexVideo()
    {
        $query = $this->getRequest()->getQuery();
        $form = new Form\MessageSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $itemModel = Api::_()->getModel('Video\Model\Video');
        $items = $itemModel->setItemList($query)->getVideoList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetVideo()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Video\Model\Video');
        $item = $itemModel->getVideo($id, array(
            'self' => array(
                '*',
                'getContentHtml()',
            ),
            'join' => array(
            ),
            'proxy' => array(
                'File\Item\File::MessageCover' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                )
            ),
        ));

        return array(
            'item' => $item,
        );
    }

    public function restPostVideo()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\MessageCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Video\Model\Video');
            $postId = $itemModel->setItem($postData)->createVideo();
            $this->flashMessenger()->addMessage('activity-create-succeed');
            $this->redirect()->toUrl('/admin/activity/' . $postId);

        } else {
            
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutVideo()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\MessageEditForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $flashMesseger = array();

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Video\Model\Video');
            $itemId = $itemModel->setItem($postData)->saveVideo();

            $this->flashMessenger()->addMessage('activity-edit-succeed');
            $this->redirect()->toUrl('/admin/activity/' . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteVideo()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\MessageDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Video\Model\Video');
            $itemModel->setItem($postData)->removeVideo();

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
