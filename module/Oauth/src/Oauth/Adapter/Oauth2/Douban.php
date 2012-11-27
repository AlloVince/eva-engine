<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;
use ZendOAuth\OAuth as ZendOAuth;
use Oauth\Exception;


class Douban extends AbstractAdapter
{
    protected $websiteName = 'Douban';

    protected $websiteProfileUrl = 'http://douban.com/people/%s/';

    protected $authorizeUrl = "https://www.douban.com/service/auth2/auth";

    protected $accessTokenUrl = "https://www.douban.com/service/auth2/token";

    protected $httpClientOptions = array(
        'callback' => 'getAccessTokenClient'
    );


    public function getAccessTokenClient($client)
    {
        $accessToken = $client->getToken();
        if(!$accessToken){
            throw new Exception\InvalidArgumentException(sprintf('No access token found'));
        }

        $accessTokenString = $accessToken->getParam('access_token');
        $client->setHeaders(array("Authorization: Bearer $accessTokenString"));
        return $client;
    }

    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        $token['remoteUserId'] = $accessToken->getParam('douban_user_id');
        return $token;
    }
}
