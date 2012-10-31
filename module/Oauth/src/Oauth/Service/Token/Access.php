<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_OAuth
 */

namespace Oauth\Service\Token;

use ZendOAuth\Client;
use ZendOAuth\Exception;
use ZendOAuth\Config\ConfigInterface as Config;
use Zend\Uri;

/**
 * @category   Zend
 * @package    Zend_OAuth
 */
class Access extends AbstractToken //\ZendOAuth\Token\Access
{
    const TOKEN_PARAM_KEY  = 'access_token';
    const EXPIRED_KEY = 'expires_in';
    const REFRESH_TOKEN_KEY = 'refresh_token';

    /**
     * Gets the value for a Token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->getParam(self::TOKEN_PARAM_KEY);
    }

    public function getExpiredTime()
    {
        $expiredTime = $this->getParam(self::EXPIRED_KEY);
        if($expiredTime && is_numeric($expiredTime)){
            return gmdate('Y-m-d H:i:s', time() + $expiredTime);
        }
    }

    public function getRefreshToken()
    {
        return $this->getParam(self::REFRESH_TOKEN_KEY);
    }

    /**
     * Get OAuth client
     *
     * @param  array $oauthOptions
     * @param  null|string $uri
     * @param  null|array|\Traversable $config
     * @param  bool $excludeCustomParamsFromHeader
     * @return Client
     */
    public function getHttpClient(array $oauthOptions = array(), $uri = null, $config = null, $excludeCustomParamsFromHeader = true)
    {
        $client = new Client($oauthOptions, $uri, $config, $excludeCustomParamsFromHeader);
        $client->setToken($this);
        return $client;
    }
}
