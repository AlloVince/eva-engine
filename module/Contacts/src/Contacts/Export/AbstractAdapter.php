<?php
    
namespace Contacts\Export;

use Contacts\Export\AdapterInterface;
use Contacts\Exception;
use Zend\Http\Client;

abstract class AbstractAdapter implements AdapterInterface
{
    protected $callback;

    protected $httpClient;

    protected $options;
    
    protected $accessToken;

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

        if(!$options['accessToken']){
            throw new Exception\InvalidArgumentException(sprintf('No accessToken found in %s', get_class($this)));
        }
        
        /*
        if(!$options['callbackUrl']){
            throw new Exception\InvalidArgumentException(sprintf('No callback url found in %s', get_class($this)));
        }
        */
        $this->setAccessToken($options['accessToken']);
        //$this->setCallback($options['callbackUrl']);

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

    /**
     * Redirect to oauth service page
     */
    public function getRequestUrl()
    {
        return $this->requestUrl = $this->requestUrl . $this->accessToken; 
    }

    /**
     * Redirect to oauth service page
     */
    public function getContacts()
    {
        $response = $this->getResponse(); 
        
        $contacts = array();
        if ($response->getStatusCode() == 200) {
            $contacts = $this->getContactsFromResponse();
        }   
    
        return $contacts;
    }

    public function __construct(array $options = array())
    {
        if($options){
            $this->setOptions($options);
        }
    }
}
