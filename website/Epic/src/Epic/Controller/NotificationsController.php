<?php
namespace Epic\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Notification\Form;
use Epic\Exception;
use Core\Auth;


class NotificationsController extends ActionController
{
    public function removeAction()
    {
        $request = $this->getRequest();
        $user = \Core\Auth::getLoginUser(); 
        
        if (!$user) {
            throw new Exception\InvalidArgumentException('User id not match');
        }

        if ($request->isPost()) {

            $postData = $this->params()->fromPost();
            $callback = $this->params()->fromPost('callback');

            $form = new \Notification\Form\NoticeDeleteForm();
            $form->bind($postData);
            if ($form->isValid()) {

                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Notification\Model\Notice');
                $itemModel->setItem($postData)->removeNotice();
                $callback = $callback ? $callback : '/notations/';
                $this->redirect()->toUrl($callback);

            } else {
                return array(
                    'post' => $postData,
                );
            }

        } else {

            $id = $this->params('id');

            $itemModel = Api::_()->getModel('Notification\Model\Notice');
            $item = $itemModel->getNotice($id,$user['id'])->toArray();

            return array(
                'callback' => $this->params()->fromQuery('callback'),
                'item' => $item,
            );
        } 
    }

    public function indexAction()
    {
        $query = $this->getRequest()->getQuery();
        $form = new Form\NoticeForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $user = \Core\Auth::getLoginUser(); 

        if (!$user) {
            throw new Exception\InvalidArgumentException('User id not match');
        }
        
        $query = array(
            'user_id' => $user['id'],
            'order' => 'read',
            'rows' => 50,
            'page' => 'page',
        );

        $itemModel = Api::_()->getModel('Notification\Model\Notice');
        $items = $itemModel->setItemList($query)->getNoticeList();
        $itemModel->markAsRead($items); 
        
        $items = $items->toArray(array(
            'self' => array(
                '*',
            ),
        ));
        $paginator = $itemModel->getPaginator();

        return array(
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }
}
