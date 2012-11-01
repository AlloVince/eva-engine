<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;
use ZendOAuth\OAuth as ZendOAuth;


class Douban extends AbstractAdapter
{
    protected $websiteName = 'Douban';
    protected $websiteProfileUrl = 'http://douban.com/people/%s/';

    protected $authorizeUrl = "https://www.douban.com/service/auth2/auth";

    protected $accessTokenUrl = "https://www.douban.com/service/auth2/token";
    protected $httpClientOptions = array(
        'requestScheme' => ZendOAuth::REQUEST_SCHEME_HEADER
    );


    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        $token['remoteUserId'] = $accessToken->getParam('douban_user_id');
        return $token;
    }
}
