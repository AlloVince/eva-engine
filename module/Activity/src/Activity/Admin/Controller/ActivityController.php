<?php
namespace Activity\Admin\Controller;

use Activity\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class ActivityController extends RestfulModuleController
{
    protected $renders = array(
        'restPutActivity' => 'activity/get',    
        'restPostActivity' => 'activity/get',    
        'restDeleteActivity' => 'activity/delete',    
    );

    public function restIndexActivity()
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

        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $items = $itemModel->setItemList($query)->getActivityList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetActivity()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $item = $itemModel->getActivity($id, array(
            'self' => array(
                '*',
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

    public function restPostActivity()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\MessageCreateForm();
        $form->useSubFormGroup()
             ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Activity\Model\Activity');
            $postId = $itemModel->setItem($postData)->createActivity();
            $this->flashMessenger()->addMessage('activity-create-succeed');
            $this->redirect()->toUrl('/admin/activity/' . $postId);

        } else {
            
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutActivity()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\MessageEditForm();
        $form->useSubFormGroup()
             ->bind($postData);

        $flashMesseger = array();

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Activity\Model\Activity');
            $itemId = $itemModel->setItem($postData)->saveActivity();

            $this->flashMessenger()->addMessage('activity-edit-succeed');
            $this->redirect()->toUrl('/admin/activity/' . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteActivity()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\MessageDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Activity\Model\Activity');
            $itemModel->setItem($postData)->removeActivity();

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
