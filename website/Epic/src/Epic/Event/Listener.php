<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Mvc
 */

namespace Epic\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Core\JobManager;
use Eva\Api;

/**
 * @category   Zend
 * @package    Zend_Mvc
 */
class Listener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('activity.model.activity.create.post', array($this, 'notificateAtUsers'));
        $this->listeners[] = $events->attach('user.model.register.register.post', array($this, 'notificateFriendRegistered'));
        $this->listeners[] = $events->attach('user.model.friend.request.post', array($this, 'notificateFriendRequest'));
        $this->listeners[] = $events->attach('user.model.friend.approve.post', array($this, 'notificateFriendApprove'));
        $this->listeners[] = $events->attach('event.model.event.create.post', array($this, 'activityCreateEvent'));
        $this->listeners[] = $events->attach('event.model.eventuser.create.post', array($this, 'activityJoinEvent'));
        $this->listeners[] = $events->attach('message.model.message.create.post', array($this, 'notificateMessageReceive'));
    }

    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function notificateMessageReceive($e)
    {
        $messageModel = $e->getTarget();
        $item = $messageModel->getItem();
        
        $conversationItem = $item->getLoadedRelationships();
        $conversation = $conversationItem['Conversation']->toArray();

        $messageId = $item->id;
        $messageBody = $item->body;

        $recipientId = $conversation['recipient_id'];
        $sender = \Core\Auth::getLoginUser();
        
        if(!$recipientId || !$sender['id']){
            return false;
        }

        JobManager::setQueue('notificate');
        JobManager::jobHandler('Epic\Jobs\NotificateMessageReceive', array(
            'sender' => $sender,
            'recipientId' => $recipientId,
            'body' => $messageBody,
        ));
    }

    public function notificateAtUsers($e)
    {
        $activityModel = $e->getTarget();
        $item = $activityModel->getItem();
        $activityId = $item->id;
        $userId = $item->user_id;
        $userNames = $item->getParser()->getUserNames();
        if(!$userNames){
            return false;
        }

        $userModel = \Eva\Api::_()->getModel('User\Model\User');
        $author = $userModel->getUser($userId);

        JobManager::setQueue('notificate');
        JobManager::jobHandler('Epic\Jobs\NotificateAtUsers', array(
            'id' => $activityId,
            'user_id' => $userId,
            'authorName' => $author['userName'],
            'userNames' => $userNames,
        ));
    }

    public function notificateFriendRegistered($e)
    {
        $userModel = $e->getTarget();
        $item = $userModel->getItem();
        $userId = $item->id;
        $userName = $item->userName;
        $inviteUserId = $item->inviteUserId;

        if(!$inviteUserId){
            return false;
        }

        $userModel = clone \Eva\Api::_()->getModel('User\Model\User');
        $author = $userModel->getUser($inviteUserId);

        JobManager::setQueue('notificate');
        JobManager::jobHandler('Epic\Jobs\NotificateFriendRegistered', array(
            'user_id' => $userId,
            'user_name' => $userName,
            'inviteUserId' => $inviteUserId,
            'inviteUserName' => $author->userName,
        ));
    }
    
    public function notificateFriendRequest($e)
    {
        $friendModel = $e->getTarget();
        $item = $friendModel->getItem();
        
        $userId = $item->user_id;
        $friendId = $item->friend_id;
        
        if(!$friendId){
            return false;
        }
        
        JobManager::setQueue('notificate');
        JobManager::jobHandler('Epic\Jobs\NotificateFriendRequest', array(
            'user_id' => $userId,
            'friend_id' => $friendId,
        ));
    }

    public function notificateFriendApprove($e)
    {
        $friendModel = $e->getTarget();
        $item = $friendModel->getItem();
        
        $userId = $item->user_id;
        $friendId = $item->friend_id;
        
        if(!$friendId){
            return false;
        }
        
        JobManager::setQueue('notificate');
        JobManager::jobHandler('Epic\Jobs\NotificateFriendApprove', array(
            'user_id' => $userId,
            'friend_id' => $friendId,
        ));
    }
    
    public function activityCreateEvent($e)
    {
        $eventModel = $e->getTarget();
        $item = $eventModel->getItem();

        $userId = $item->user_id;
        $eventId = $item->id;

        $content = $this->getActivityContent(
            array(
                'event' => $item,
            ),
            'createevent'
        );

        $postData = array(
            'messageType' =>"original",
            'content' => $content,
        );

        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $postId = $itemModel->setItem($postData)->createActivity();  
    }

    public function activityJoinEvent($e)
    {
        $eventUserModel = $e->getTarget();
        $item = $eventUserModel->getItem();

        $userId = $item->user_id;
        $eventId = $item->event_id;

        $eventModel = \Eva\Api::_()->getModel('Event\Model\Event');
        $item = $eventModel->getEventdata($eventId);

        $content = $this->getActivityContent(
            array(
                'event' => $item,
            ),
            'joinevent'
        );

        $postData = array(
            'messageType' =>"original",
            'content' => $content,
        );

        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $postId = $itemModel->setItem($postData)->createActivity(); 
    }

    public function getActivityContent($data, $template)
    {
        $view = new \Zend\View\Renderer\PhpRenderer();
        $resolver = new \Zend\View\Resolver\TemplateMapResolver();
        $resolver->setMap(array(
            'mailTemplate' => __DIR__ . "/../../../view/activity/$template.phtml"
        ));
        $view->setResolver($resolver);
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('mailTemplate')
            ->setVariables($data);
        
        return $view->render($viewModel);
    }
}
