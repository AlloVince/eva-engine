<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;
use ZendOAuth\OAuth;

class Google extends AbstractAdapter
{
    protected $authorizeUrl = "https://accounts.google.com/o/oauth2/auth";
    protected $accessTokenUrl = "https://accounts.google.com/o/oauth2/token";

    protected $defaultOptions = array(
        'requestScheme' => OAuth::REQUEST_SCHEME_POSTBODY,
        'scope' => 'https://www.google.com/m8/feeds/',
    );
}
