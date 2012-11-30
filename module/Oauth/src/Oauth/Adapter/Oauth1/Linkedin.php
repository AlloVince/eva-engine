<?php
    
namespace Oauth\Adapter\Oauth1;

use Oauth\Adapter\Oauth1\AbstractAdapter;
use ZendOAuth\Token\Access as AccessToken;


class Linkedin extends AbstractAdapter
{
    protected $websiteName = 'LinkedIn';

    protected $websiteProfileUrl = '';

    protected $requestTokenUrl = "https://api.linkedin.com/uas/oauth/requestToken";

    protected $authorizeUrl = "https://api.linkedin.com/uas/oauth/authenticate";

    protected $accessTokenUrl = "https://api.linkedin.com/uas/oauth/accessToken";

    public function accessTokenToArray(AccessToken $accessToken)
    {
        $token = parent::accessTokenToArray($accessToken);
        //$token['remoteUserId'] = $accessToken->getParam('douban_user_id');
        return $token;
    }
}
