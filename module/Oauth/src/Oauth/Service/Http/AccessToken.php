<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_OAuth
 */

 namespace Oauth\Service\Http;

use Oauth\Service\Token;
use Zend\Http;
use ZendOAuth\OAuth;
use ZendOAuth\Http as HTTPClient;

/**
 * @category   Zend
 * @package    Zend_OAuth
 */
class AccessToken extends HTTPClient 
{
    /**
     * Singleton instance if required of the HTTP client
     *
     * @var \Zend\Http\Client
     */
    protected $_httpClient = null;

    /**
     * Initiate a HTTP request to retrieve an Access Token.
     *
     * @return \ZendOAuth\Token\Access
     */
    public function execute()
    {
        $defaultParams = $this->getParameters();
        $params = array(
            'grant_type' => 'authorization_code',
			'client_id' => $this->_consumer->getConsumerKey(),
            'client_secret' => $this->_consumer->getConsumerSecret(),
            'redirect_uri' => $this->_consumer->getCallbackUrl(),
		);


        $params = array_merge($defaultParams, $params);
        
        $response = $this->startRequestCycle($params);

        $return   = new Token\Access($response);
        return $return;
    }

    /**
     * Generate and return a HTTP Client configured for the Header Request Scheme
     * specified by OAuth, for use in requesting an Access Token.
     *
     * @param  array $params
     * @return Zend\Http\Client
     */
    public function getRequestSchemeHeaderClient(array $params)
    {
        $client      = OAuth::getHttpClient();

        $client->setUri($this->_consumer->getAccessTokenUrl());
        $client->setMethod($this->_preferredRequestMethod);

        return $client;
    }
}
