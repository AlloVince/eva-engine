<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;

class Renren extends AbstractAdapter
{
    protected $authorizeUrl = "https://graph.renren.com/oauth/authorize";
    protected $accessTokenUrl = "https://graph.renren.com/oauth/token";

    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        $user = $accessToken->getParam('user');
        if($user) {
            $user = (array) $user;
            $token['remoteUserId'] = $user['id'];
            $token['remoteUserName'] = $user['name'];
            $token['remoteExtra'] = \Zend\Json\Json::encode($user);
        }
        return $token;
    }
}
