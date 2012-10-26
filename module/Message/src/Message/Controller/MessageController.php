<?php
namespace Message\Controller;

use Message\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class MessageController extends RestfulModuleController
{
    protected $renders = array(
        'restPostMessage' => 'blank',    
        'restDeleteMessage' => 'blank',
    );
    
    protected $addResources = array(
    );

    public function restPostMessage()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\MessageForm();
        $form->useSubFormGroup()
            ->bind($postData);

        $callback = $this->params()->fromPost('callback','/message/messages/');
        if ($form->isValid()) {
            $messageData = $form->getData();

            $userModel = Api::_()->getModel('User\Model\User');
            $recipient = $userModel->getUser($messageData['Conversation']['recipient_id']); 
            $sender = \Core\Auth::getLoginUser();

            if (!isset($recipient['id']) || !isset($sender['id']) || $recipient['id'] == $sender['id']) {
                exit;
            }

            if (!is_numeric($messageData['Conversation']['recipient_id'])) {
                $messageData['Conversation']['recipient_id'] = $recipient['id'];
            }

            $itemModel = Api::_()->getModel('Message\Model\Message');
            $messageId = $itemModel->setItem($messageData)->createMessage();
            $this->flashMessenger()->addMessage('message-create-succeed');
            
            $this->redirect()->toUrl($callback);
        } else {
            
        }

        return array(
            'form' => $form,
            'message' => $messageData,
        );
    }

    public function restDeleteMessage()
    {
        $postData = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback','/message/messages/');

        $form = new Form\ConversationDeleteForm();
        $form->bind($postData);
        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Message\Model\Conversation');
            
            $conversation = $itemModel->getConversation($postData['id'])->toArray();
            
            $user = \Core\Auth::getLoginUser(); 
            if ($user['id'] != $conversation['author_id']) {
                exit; 
            }
            
            $itemModel->setItem($postData)->removeConversation();

            $this->redirect()->toUrl($callback);

        } else {
            return array(
                'conversation' => $postData,
            );
        }
    }
}
