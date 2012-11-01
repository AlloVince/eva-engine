<?php
    
namespace Oauth\Adapter\Oauth1;

use Oauth\Adapter\Oauth1\AdapterInterface;
use Oauth\Exception;
use ZendOAuth\OAuth as ZendOAuth;
use ZendOAuth\Consumer;
use ZendOAuth\Token\Access as AccessToken;


abstract class AbstractAdapter extends \Oauth\Adapter\AbstractAdapter implements AdapterInterface
{
    public function setOptions(array $options = array())
    {
		$defaultOptions = array(
            'requestScheme' => ZendOAuth::REQUEST_SCHEME_HEADER,
			'version' => '1.0', 
			'signatureMethod' => 'HMAC-SHA1', 
            'callbackUrl' =>  $this->getCallback(),
            'consumerKey' => $this->getConsumerKey(),
            'consumerSecret' => $this->getConsumerSecret(),
            'requestTokenUrl' => $this->requestTokenUrl,
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

    public function accessTokenToArray(AccessToken $accessToken)
    {
        return array(
            'adapterKey' => $this->getAdapterKey(),
            'token' => $accessToken->getToken(),
            'tokenSecret' => $accessToken->getTokenSecret(),
            'version' => 'Oauth1',
        );
    }


    public function arrayToAccessToken(array $accessTokenArray)
    {
        $accessToken = new AccessToken();
        $accessToken->setToken($accessTokenArray['token']);
        $accessToken->setTokenSecret($accessTokenArray['tokenSecret']);
        return $accessToken;
    }
}
