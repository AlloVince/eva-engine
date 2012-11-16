<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Mvc
 */

namespace User\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

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
        $this->listeners[] = $events->attach('user.model.register.register.post', array($this, 'onRegisterPost'));
        $this->listeners[] = $events->attach('payment.model.log.logstep.response', array($this, 'onPaymentLogstepResponse'));
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

    public function onRegisterPost($e)
    {
        $codeModel = \Eva\Api::_()->getModel('User\Model\Code');
        $codeModel->createActiveCode();

        $inviteModel = \Eva\Api::_()->getModel('User\Model\Invite');
        $inviteModel->updateInviteUser();
    }

    public function onPaymentLogstepResponse($e)
    {
        $userId = $e->getTarget()->getItem()->user_id;
        $data = $e->getTarget()->getItem()->unserializeRequestData();
        
        if ($userId && isset($data['roleKey']) && isset($data['days'])) {
            $itemModel = \Eva\Api::_()->getModel('User\Model\RoleUser');
            $itemModel->upgradeRoleUser($userId, $data['roleKey'], $data['days']);
        } 
        
    }
}
