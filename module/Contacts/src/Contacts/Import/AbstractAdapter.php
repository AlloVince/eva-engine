<?php
    
namespace Contacts\Import;

use Contacts\Import\AdapterInterface;
use Contacts\Exception;
use Zend\Http\Client;
use Core\Auth;
use Eva\Api;

abstract class AbstractAdapter implements AdapterInterface
{
    protected $callback;

    protected $httpClient;

    protected $options;
    
    protected $accessToken;
    
    protected $contacts;
    
    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
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

        $this->setAccessToken($options['accessToken']);

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
        if(!$this->accessToken){
            throw new Exception\InvalidArgumentException(sprintf('No accessToken found in %s', get_class($this)));
        }

        return $this->requestUrl = $this->requestUrl . $this->accessToken; 
    }

    public function getContacts()
    {
        $response = $this->getResponse(); 

        $contacts = array();
        if ($response->getStatusCode() == 200) {
            $contacts = $this->getContactsFromResponse();
        }   

        return $this->contacts = $contacts;
    }

    public function __construct(array $options = array())
    {
        if($options){
            $this->setOptions($options);
        }
    }
}
