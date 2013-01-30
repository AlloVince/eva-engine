<?php
namespace Notification\Admin\Controller;

use Notification\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class NotificationController extends RestfulModuleController
{
    protected $renders = array(
        'restPutNotification' => 'notification/get',    
        'restPostNotification' => 'notification/get',    
        'restDeleteNotification' => 'remove/get',    
    );
    
    public function restIndexNotification()
    {
        $query = $this->getRequest()->getQuery();
        $form = new Form\NotificationSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $itemModel = Api::_()->getModel('Notification\Model\Notification');
        $items = $itemModel->setItemList($query)->getNotificationList();
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetNotification()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Notification\Model\Notification');
        $item = $itemModel->getNotification($id, array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Count' => array(
                    'self' => array(
                        '*',
                    )
                ),
            ),
            'proxy' => array(
                'Notification\Item\Notification::Cover' => array(
                    '*',
                    'getThumb()'
                ),
            ),
        ));

        return array(
            'item' => $item,
        );
    }
    
    

    public function restPostNotification()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\NotificationCreateForm();
        $form->useSubFormGroup()
            ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Notification\Model\Notification');
            $user = \Core\Auth::getLoginUser('Auth_Admin');
            $notificationId = $itemModel->setItem($postData)->createNotification();
            $this->flashMessenger()->addMessage('notification-create-succeed');
            $this->redirect()->toUrl('/admin/notification/' . $notificationId);

        } else {

        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutNotification()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\NotificationEditForm();
        $form->useSubFormGroup()
            ->bind($postData);

        $flashMesseger = array();

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Notification\Model\Notification');
            $notificationId = $itemModel->setItem($postData)->saveNotification();

            $this->flashMessenger()->addMessage('notification-edit-succeed');
            $this->redirect()->toUrl('/admin/notification/' . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }

    public function restDeleteNotification()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\NotificationDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Notification\Model\Notification');
            $itemModel->setItem($postData)->removeNotification();

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
