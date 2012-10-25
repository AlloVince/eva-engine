<?php
namespace Message\Controller;

use Message\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class MessagesController extends RestfulModuleController
{
    protected $renders = array(
        'restPutMessages' => 'messages/get',    
        'restPostMessages' => 'messages/get',    
        'restDeleteMessages' => 'remove/get',    
    );
    
    protected $addResources = array(
        'new',
        'unreadcount',
    );

    public function restGetMessagesUnreadcount()
    {
        $mine = \Core\Auth::getLoginUser(); 
        
        $count = 0;

        if ($mine) {
            $query = array(
                'author_id' => $mine['id'],
                'noLimit' => true,
            );
            
            $itemModel = Api::_()->getModel('Message\Model\Index');
            $items = $itemModel->setItemList($query)->getIndexList();
      
            if ($items) {
                foreach ($items as $item) {
                    $count += $item->messageCount;
                }
            }
        }
        
        return $count;
    }

    public function restGetMessagesNew()
    {
    }

    public function restIndexMessages()
    {
        $query = $this->getRequest()->getQuery();
        $form = new Form\IndexForm();
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

        if ($user['id'] != $query['author_id']) {
            exit; 
        }

        $itemModel = Api::_()->getModel('Message\Model\Index');
        $items = $itemModel->setItemList($query)->getIndexList();
        $items = $items->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'User' => array(
                    'id',
                    'userName',
                ),
                'Conversation' => array(
                    'self' => array(
                        'message_id',
                    ),
                    'join' => array(
                        'Message' => array(
                            '*',
                        ),
                    ),
                ),
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

    public function restGetMessages()
    {
        $id = $this->params('id');
        $userModel = Api::_()->getModel('User\Model\User');
        $user = $userModel->getUser($id); 

        $query = $this->getRequest()->getQuery();
        $form = new Form\ConversationSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }
        
        if (!isset($query['author_id'])) {
            $author = \Core\Auth::getLoginUser();
            $query['author_id'] = $author['id']; 
        }
            
        $query['user_id'] = $user['id'];

        $itemModel = Api::_()->getModel('Message\Model\Conversation');
        $items = $itemModel->setItemList($query)->getConversationList();
        $itemModel->markAsRead($items);

        $items = $items->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Sender' => array(
                    'userName',
                ),
                'Recipient' => array(
                    'userName',
                ),
                'Message' => array(
                    'body',
                ),
            ),
        ));
        $paginator = $itemModel->getPaginator();
        
        return array(
            'user' => $user,
            'items' => $items,
            'item' => array('Conversation' => array('recipient_id' => $user['id'])),
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restPostMessages()
    {
        $callback = $this->params()->fromQuery('callback');
        
        $postData = $this->params()->fromPost();
        $form = new Form\MessageForm();
        $form->useSubFormGroup()
            ->bind($postData);

        if ($form->isValid()) {
            $messageData = $form->getData();

            $userModel = Api::_()->getModel('User\Model\User');
            $recipient = $userModel->getUser($messageData['Conversation']['recipient_id']); 
            $sender = \Core\Auth::getLoginUser();

            if (!isset($recipient['id']) || !isset($sender['id']) || $recipient['id'] == $sender['id']) {
                exit;
            }
            
            $itemModel = Api::_()->getModel('Message\Model\Message');
            $messageId = $itemModel->setItem($messageData)->createMessage();
            $this->flashMessenger()->addMessage('message-create-succeed');
            
            if ($callback) {
                $this->redirect()->toUrl($callback);
            } else {
                $this->redirect()->toUrl('/message/messages/' . $recipient['id']);
            }
        } else {
            
        }

        return array(
            'form' => $form,
            'message' => $messageData,
        );
    }

    public function restDeleteMessages()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');

        $form = new Form\PostDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Message\Model\Conversation');
            $itemModel->setItem($postData)->removeConversation();

            if($callback){
                $this->redirect()->toUrl($callback);
            } else {
                $this->redirect()->toUrl('/message/messages/');
            }

        } else {
            return array(
                'post' => $postData,
            );
        }
    }
}
