<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use Oauth\Service\Token\Access as AccessToken;

class Twitter extends AbstractAdapter
{
    //protected $accessTokenFormat = 'pair';

    protected $authorizeUrl = "https://oauth.twitter.com/2/authorize";
    protected $accessTokenUrl = "https://oauth.twitter.com/2/access_token";

}
