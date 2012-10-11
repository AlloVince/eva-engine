<?php
    
namespace Oauth\Adapter\Oauth1;

use Oauth\Adapter\AdapterInterface;
use Oauth\Adapter\Oauth1\AbstractAdapter;
use ZendOAuth\Token\Access as AccessToken;


class Douban extends AbstractAdapter implements AdapterInterface
{
    protected $requestTokenUrl = "https://www.douban.com/service/auth/request_token";

    protected $authorizeUrl = "https://www.douban.com/service/auth/authorize";

    protected $accessTokenUrl = "https://www.douban.com/service/auth/access_token";

    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        $token['remoteUserId'] = $accessToken->getParam('douban_user_id');
        return $token;
    }
}
