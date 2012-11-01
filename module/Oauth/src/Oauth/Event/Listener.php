<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Mvc
 */

namespace Oauth\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Eva\Api;
use Oauth\OauthService;

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
        $userModel     = $e->getTarget();
        $userItem = $userModel->getItem();
        $itemModel = Api::_()->getModel('Oauth\Model\Accesstoken');
        $itemModel->setUser($userItem);

        $oauth = new OauthService();
        $accessToken = $oauth->getStorage()->getAccessToken();
        $itemModel->setItem($accessToken)->bindToken();
    }
}
