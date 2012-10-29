<?php
namespace Message\Controller;

use Message\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class MessagesController extends RestfulModuleController
{
    protected $renders = array(
        'restGetMessagesRemove' => 'messages/delete',    
    );
    
    protected $addResources = array(
        'remove',
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
                    $count += $item->unreadCount;
                }
            }
        }
        
        return $count;
    }

    public function restGetMessagesNew()
    {
    }
    
    public function restGetMessagesRemove()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Message\Model\Conversation');
        $item = $itemModel->getConversation($id)->toArray();

        $user = \Core\Auth::getLoginUser(); 
        if ($user['id'] != $item['author_id']) {
            exit; 
        }

        return array(
            'callback' => $this->params()->fromQuery('callback'),
            'item' => $item,
        ); 
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
}
