<?php
    
namespace Contacts\Import;

use Contacts\Import\AdapterInterface;
use Contacts\Exception;
use Zend\Http\Client;
use Zend\Cache\PatternFactory;
use Core\Auth;
use Eva\Api;

abstract class AbstractAdapter implements AdapterInterface
{
    protected $callback;

    protected $httpClient;

    protected $options;
    
    protected $accessToken;
    
    protected $contacts;
    
    protected $cache;
    
    protected $cacheConfig;

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
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

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function getAdapterKey()
    {
        $className = get_class($this);
        $className = explode('\\', $className);
        return strtolower(array_pop($className));
    }

    public function setOptions(array $options = array())
    {
		$defaultOptions = array(
            'accessToken' => $this->accessToken,
		);

        $options = array_merge($defaultOptions, $options);

        if(!$options['accessToken']){
            throw new Exception\InvalidArgumentException(sprintf('No accessToken found in %s', get_class($this)));
        }
        
        if(!$options['cacheConfig']){
            throw new Exception\InvalidArgumentException(sprintf('No cache config found in %s', get_class($this)));
        }
        
        $this->setAccessToken($options['accessToken']);
        $this->setCacheConfig($options['cacheConfig']);

        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getHttpClient()
    {
        if ($this->httpClient) {
            return $this->httpClient;
        }
        
        $client = new Client();
        $client->setUri($this->getRequestUrl());
        $client->setOptions(array(
            'maxredirects' => 0,
            'sslverifypeer' => false,
            'timeout'      => 30
        ));
        $client->send(); 

        return $this->httpClient = $client;
    }

    public function getRequest()
    {
        return $this->getHttpClient()->getRequest(); 
    }

    public function getResponse()
    {
        return $this->response = $this->getHttpClient()->getResponse(); 
    }

    public function getRequestUrl()
    {
        return $this->requestUrl = $this->requestUrl . $this->accessToken; 
    }
    
    public function getUserContactsInfo($contacts)
    {
        if (!$contacts) {
            return array();
        }
        
        $userModel = Api::_()->getModel('User\Model\User');
        $mine = Auth::getLoginUser();
        $mine = $userModel->getUser($mine['id']);
        
        if (!$mine) {
            return false;
        }

        $service = $this->getAdapterKey();   
        
        if (isset($contacts[$service])) {
            $contacts = $contacts[$service];
        }
        
        $emails = array();
        $outSiteContacts = array();   
        foreach ($contacts as $user) {
            if ($user['email'] == $mine['email']) {
                continue; 
            }
            $outSiteContacts[$user['email']] = $user;
            $emails[] = $user['email'];  
        }
        
        $selectQuery = array(
            'emails' => $emails,
            'rows' => 1000,
        );
        $items = $userModel->setItemList($selectQuery)->getUserList(); 
        $onSiteContacts = $items->toArray();

        if (!$onSiteContacts) {
            return array(
                'contactsCount' => count($contacts),
                'outSiteContactsCount' => count($outSiteContacts),
                'outSiteContacts' => $outSiteContacts,
            );
        }

        $onSiteFriends = array();
        foreach ($onSiteContacts as $key=>$user) {
            $onSiteFriends[$user['id']] = $user;
            unset($outSiteContacts[$user['email']]);
        }
        $onSiteContacts = $onSiteFriends;
    
        $selectQuery = array(
            'from_user_id' => $mine['id'],
            'relationshiopStatus' => 'approved',
            'rows' => 1000,
        );

        $itemModel = Api::_()->getModel('User\Model\Friend');
        $items = $itemModel->setItemList($selectQuery)->getFriendList();
        $friends = $items->toArray();
        
        if (!$friends) {
            return array(
                'contactsCount' => count($contacts),
                'outSiteContactsCount' => count($outSiteContacts),
                'onSiteContactsCount' => count($onSiteContacts),
                'outSiteContacts' => $outSiteContacts,
                'onSiteContacts' => $onSiteContacts,
            );   
        }

        $onSiteFriends = array();
        foreach ($friends as $friend) {
            if (isset($onSiteContacts[$friend['to_user_id']])) {
                $onSiteFriends = $onSiteContacts[$friend['to_user_id']];
                unset($onSiteContacts[$friend['to_user_id']]);
            } 
        }

        if (!$friends) {
            return array(
                'contactsCount' => count($contacts),
                'outSiteContactsCount' => count($outSiteContacts),
                'onSiteContactsCount' => count($onSiteContacts),
                'onSiteFriendsCount' => $onSiteFriendsCount,
                'outSiteContacts' => $outSiteContacts,
                'onSiteContacts' => $onSiteContacts,
                'onSiteFriends' => $onSiteFriends,
                $service => $contacts,
            );   
        }
    }

    public function getContacts()
    {
        if ($this->loadContacts()) {
            return $this->contacts;
        }

        $response = $this->getResponse(); 

        $contacts = array();
        if ($response->getStatusCode() == 200) {
            $contacts = $this->getContactsFromResponse();
        }   

        return $this->contacts = $contacts;
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

        $user = Auth::getLoginUser();
        if(!$user){
            return false;
        }

        $service = $this->getAdapterKey();

        $cache = $this->initCache();
        $key = "contacts_{$service}_info_" . $user['id'];
        
        $cache->setItem($key, $contacts);
        
        return true;
    }

    public function loadContacts()
    {
        $user = Auth::getLoginUser();
        if(!$user){
            return false;
        }

        $service = $this->getAdapterKey();

        $cache = $this->initCache();
        $key = "contacts_{$service}_info_" . $user['id'];

        return $this->contacts = $cache->getItem($key);
    }

    public function __construct(array $options = array())
    {
        if($options){
            $this->setOptions($options);
        }
    }
}
