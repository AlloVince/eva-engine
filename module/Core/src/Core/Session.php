<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Session
 */

namespace Core;

use Eva\Api;
use Zend\Session\Configuration\SessionConfiguration;
use Zend\Session\SessionManager;
use Zend\Session\Container;

/**
 * Session ManagerInterface implementation utilizing ext/session
 *
 * @category   Zend
 * @package    Zend_Session
 */
class Session
{

    protected static $sessionManager;
    protected static $sessionContainer;

    protected static $sessionStarted = false;
    protected static $sessionInited = false;

    public static function start()
    {
        if(true === self::$sessionStarted){
            return true;
        }
        self::initSession();
        if(false === self::$sessionStarted){
            self::$sessionManager->start();
            return self::$sessionStarted = true;
        }
    }

    public static function setSession($key, $value)
    {
        self::start();
        self::$sessionContainer->offsetSet($key, $value);
    }

    public static function unsetSession($key)
    {
        self::start();
        self::$sessionContainer->offsetUnset($key);
    }

    public static function getSession($key)
    {
        self::start();
        if(self::$sessionContainer->offsetExists($key)){
            return self::$sessionContainer->offsetGet($key);
        }
        return null;
    }

    public static function getSessionManager()
    {
        return self::$sessionManager;
    }

    public static function initSession(array $config = array())
    {
        if(true === self::$sessionInited){
            return true;
        }

        $defaultConfig = Api::_()->getConfig();
        $sessionNamespace = 'Eva';
        if(isset($defaultConfig['session'])){
            $config = array_merge($defaultConfig['session'], $config);

            if(isset($defaultConfig['session']['namespace']) && $defaultConfig['session']['namespace']){
                $sessionNamespace = $defaultConfig['session']['namespace'];
            }
        }
        $config = new SessionConfiguration($config);
        self::$sessionManager = $manager = new SessionManager($config);
        self::$sessionContainer = new Container($sessionNamespace, $manager);
        return self::$sessionInited = true;
    }
}
