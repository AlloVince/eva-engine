<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use ZendOAuth\OAuth;
use Oauth\Service\Token\Access as AccessToken;

class Google extends AbstractAdapter
{
    protected $authorizeUrl = "https://accounts.google.com/o/oauth2/auth";
    protected $accessTokenUrl = "https://accounts.google.com/o/oauth2/token";

    protected $defaultOptions = array(
        'requestScheme' => OAuth::REQUEST_SCHEME_POSTBODY,
        'scope' => 'https://www.googleapis.com/auth/userinfo.profile',
    );

    protected $httpClientOptions = array(
        'callback' => 'getAccessTokenClient',
    );

    public function getAccessTokenClient($client)
    {
        $accessToken = $client->getToken();
        if(!$accessToken){
            throw new Exception\InvalidArgumentException(sprintf('No access token found'));
        }

        $accessTokenString = $accessToken->getParam('access_token');
        $client->setHeaders(array("Authorization: OAuth $accessTokenString"));
        return $client;
    }

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
        $client->setUri('https://www.googleapis.com/oauth2/v2/userinfo');
        $response = $client->send();
        $data = $this->parseJsonpResponse($response);
        return isset($data['id']) ? $data['id'] : null;
    }
}
