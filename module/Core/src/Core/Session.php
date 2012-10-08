<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Core;

use Eva\Api;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;

/**
 * Core Session
 *
 * @category   Core
 * @package    Core_Session
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
        self::start();
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
        $config = new SessionConfig($config);
        self::$sessionManager = $manager = new SessionManager($config);
        self::$sessionContainer = new Container($sessionNamespace, $manager);
        return self::$sessionInited = true;
    }
}
