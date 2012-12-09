<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;

class Disqus extends AbstractAdapter
{
    protected $authorizeUrl = "https://disqus.com/api/oauth/2.0/authorize/";
    protected $accessTokenUrl = "https://disqus.com/api/oauth/2.0/access_token/";

    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        $token['remoteUserId'] = $token['user_id'];
        $token['remoteUserName'] = $user['username'];
        return $token;
    }
}
