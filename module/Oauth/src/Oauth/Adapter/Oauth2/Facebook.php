<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;

class Facebook extends AbstractAdapter
{
    protected $websiteName = 'Facebook';
    protected $websiteProfileUrl = 'http://www.facebook.com/%s/';

    protected $accessTokenFormat = 'pair';

    protected $authorizeUrl = "https://www.facebook.com/dialog/oauth";
    protected $accessTokenUrl = "https://graph.facebook.com/oauth/access_token";

    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        $expiredTime = $accessToken->getParam('expires');
        if($expiredTime) {
            $token['expiredTime'] =  gmdate('Y-m-d H:i:s', time() + $expiredTime);
        }
        return $token;
    }
}
