<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;

class Twitter extends AbstractAdapter
{
    //protected $accessTokenFormat = 'pair';

    protected $authorizeUrl = "https://oauth.twitter.com/2/authorize";
    protected $accessTokenUrl = "https://oauth.twitter.com/2/access_token";

    /*
    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        $expiredTime = $accessToken->getParam('expires');
        if($expiredTime) {
            $token['expiredTime'] =  gmdate('Y-m-d H:i:s', time() + $expiredTime);
        }
        return $token;
    }
    */
}
