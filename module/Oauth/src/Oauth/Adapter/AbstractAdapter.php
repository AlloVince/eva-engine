<?php
    
namespace Oauth\Adapter;

use Oauth\Adapter\AdapterInterface;
use Oauth\Exception;
use ZendOAuth\OAuth as ZendOAuth;
use Oauth\Service\Consumer;
use ZendOAuth\Token\Access as AccessToken;


abstract class AbstractAdapter implements AdapterInterface
{
    protected $callback;

    protected $consumerKey;

    protected $consumerSecret;

    protected $consumer;

    protected $options;

    protected $websiteName;

    protected $websiteProfileUrl;

    protected $accessToken;

    public function getWebsiteName()
    {
        return $this->websiteName;
    }

    public function setWebsiteName($websiteName)
    {
        $this->websiteName = $websiteName;
        return $this;
    }

    public function getWebsiteProfileUrl()
    {
        $accessToken = $this->getAccessToken();
        return sprintf($this->websiteProfileUrl, $accessToken->getParam('remoteUserId'));
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    public function setConsumerKey($consumerKey)
    {
        $this->consumerKey = $consumerKey;
        return $this;
    }

    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }

    public function setConsumerSecret($consumerSecret)
    {
        $this->consumerSecret = $consumerSecret;
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
            'requestScheme' => ZendOAuth::REQUEST_SCHEME_HEADER,
            'version' => '2.0', 
            'callbackUrl' =>  $this->getCallback(),
            'consumerKey' => $this->getConsumerKey(),
            'consumerSecret' => $this->getConsumerSecret(),
            'authorizeUrl' => $this->authorizeUrl,
            'accessTokenUrl' => $this->accessTokenUrl,
		);

        $options = array_merge($defaultOptions, $options);

        if(!$options['consumerKey']){
            throw new Exception\InvalidArgumentException(sprintf('No consumer key found in %s', get_class($this)));
        }

        if(!$options['consumerSecret']){
            throw new Exception\InvalidArgumentException(sprintf('No consumer secret found in %s', get_class($this)));
        }

        if(!$options['callbackUrl']){
            throw new Exception\InvalidArgumentException(sprintf('No callback url found in %s', get_class($this)));
        }

        $this->setConsumerKey($options['consumerKey']);
        $this->setConsumerSecret($options['consumerSecret']);
        $this->setCallback($options['callbackUrl']);

        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getConsumer()
    {
        if($this->consumer){
            return $this->consumer;
        }

        $consumer = new Consumer($this->getOptions());
        //to void the error :  make sure the "sslcapath" option points to a valid SSL certificate directory
        $consumer->getHttpClient()->setOptions(array(
            'sslverifypeer' => false
        ));

        return $this->consumer = $consumer;

    }

    public function getHttpClient()
    {
        return $this->getConsumer()->getHttpClient(); 
    }

    public function getRequest()
    {
        return $this->getConsumer()->getHttpClient()->getRequest(); 
    }

    public function getResponse()
    {
        return $this->getConsumer()->getHttpClient()->getResponse(); 
    }

    /**
     * Redirect to oauth service page
     */
    public function getRequestToken()
	{
        return $this->getConsumer()->getRequestToken();
	}

    /**
     * Redirect to oauth service page
     */
    public function getRequestTokenUrl()
    {
        return $this->getConsumer()->getRedirectUrl();
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
    * Redirect to oauth service page
    */
    public function getAccessToken($queryData = null, $token = null, $httpMethod = null, $request = null)
    {
        if($this->accessToken){
            return $this->accessToken;
        }
        return $this->accessToken = $this->getConsumer()->getAccessToken($queryData, $token, $httpMethod, $request);
    }


    public function __construct($options = array())
    {
        if($options){
            $this->setOptions($options);
        }
    }
}
