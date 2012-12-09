<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;

class Netease extends AbstractAdapter
{
    protected $authorizeUrl = "https://api.t.163.com/oauth2/authorize";
    protected $accessTokenUrl = "https://api.t.163.com/oauth2/access_token";


    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        $token['remoteUserId'] = $accessToken->getParam('uid');
        return $token;
    }
}
