<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;

class Kaixin extends AbstractAdapter
{
    protected $authorizeUrl = "http://api.kaixin001.com/oauth2/authorize";
    protected $accessTokenUrl = "https://api.kaixin001.com/oauth2/access_token";


    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        if(!isset($token['remoteUserId']) || !$token['remoteUserId']){
            $token['remoteUserId'] = $this->getRemoteUserId();
        }
        return $token;
    }

    public function getRemoteUserId()
    {
        $client = $this->getHttpClient();
        $client->setUri('https://api.kaixin001.com/users/me.json');
        $response = $client->send();
        $data = $this->parseJsonResponse($response);
        return isset($data['uid']) ? $data['uid'] : null;
    }
}
