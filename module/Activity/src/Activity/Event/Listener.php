<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Mvc
 */

namespace Activity\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
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
        //$this->listeners[] = $events->attach('activity.model.follow.create.post', array($this, 'onFollowUser'));
        //$this->listeners[] = $events->attach('activity.model.follow.remove.post', array($this, 'onUnfollowUser'));

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

    public function onFollowUser($event)
    {
        $followModel = $event->getTarget();
        $followItem = $followModel->getItem();
        if($followItem->relationshipStatus == 'double') {
            $userModel = Api::_()->getModel('User\Model\Friend');
            $userModel->setItem(array(
                'user_id' => $followItem->follower_id,
                'friend_id' => $followItem->user_id,
            ));
            $userModel->createFriend();
        }
    }

    public function onUnfollowUser($event)
    {
        $followModel = $event->getTarget();
        $followItem = $followModel->getItem();
        $userModel = Api::_()->getModel('User\Model\Friend');
        $userModel->setItem(array(
            'user_id' => $followItem->follower_id,
            'friend_id' => $followItem->user_id,
        ));
        $userModel->removeFriend();
    }
}
