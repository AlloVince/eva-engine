<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Authentication
 */

namespace Contacts\Storage;

use Core\Auth;
use Eva\Api;

/**
 * @category   Zend
 * @package    Zend_Authentication
 * @subpackage Storage
 */
class Cache implements StorageInterface
{
    protected $cacheConfig;
    
    protected $cacheKey;

    protected $user;
    
    protected $contacts;
    
    protected $cache;
    
    public function __construct($service, $config)
    {
        $this->setCacheConfig($config);
        $this->initCache();
        $user = Auth::getLoginUser();
        $this->setUser($user);
        $this->service = $service;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }    
    
    public function getCacheKey()
    {
        return $this->cacheKey ? $this->cacheKey : ("contacts_{$this->service}_info_" . $this->user['id']);
    }

    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;
        return $this;
    }

    public function getCacheConfig()
    {
        return $this->cacheConfig;
    }

    public function setCacheConfig($cacheConfig)
    {
        $this->cacheConfig = $cacheConfig;
        return $this;
    }

    public function initCache()
    {
        $config = $this->getCacheConfig();
        
        if (!$config['enable']) {
            return false;
        }

        if ($this->cache) {
            return $this->cache;
        }

        $cache = \Eva\Cache\StorageFactory::factory(array(
            'adapter' => $config['adapter'],
            'plugins' => $config['plugins'],
        ));

        return $this->cache = $cache;
    }

    public function saveContacts($contacts = array())
    {
        if (!$contacts) {
            return array();
        }   

        $user = $this->getUser();
        if(!$user){
            return false;
        }

        $cache = $this->initCache();
        $key = $this->getCacheKey();

        $cache->setItem($key, $contacts);

        return true;
    }

    public function loadContacts()
    {
        $user = $this->getUser();
        if(!$user){
            return false;
        }

        $cache = $this->initCache();
        $key = $this->getCacheKey();
        
        return $this->contacts = $cache->getItem($key);
    }    
    
    public function removeContacts($email)
    {
        if (!$email) {
            return false;
        }

        if (!$this->contacts) {
            $this->loadContacts();
        }    
    
        $contacts = $this->contacts;
        
        $res = array(); 

        foreach ($contacts as $key=>$user) {
            if ($user['email'] == $email) {
                continue; 
            } 
        
            $res[] = $user;
        }
    
        $this->saveContacts($res);
    }
}
