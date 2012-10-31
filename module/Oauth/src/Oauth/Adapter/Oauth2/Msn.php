<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use ZendOAuth\OAuth;

class Msn extends AbstractAdapter
{
    protected $authorizeUrl = "https://oauth.live.com/authorize";
    protected $accessTokenUrl = "https://oauth.live.com/token";

    protected $defaultOptions = array(
        'requestScheme' => OAuth::REQUEST_SCHEME_POSTBODY,
        'scope' => 'wl.signin wl.basic',
    );
}
