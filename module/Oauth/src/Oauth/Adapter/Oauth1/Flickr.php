<?php
    
namespace Oauth\Adapter\Oauth1;

use Oauth\Adapter\Oauth1\AbstractAdapter;
use ZendOAuth\OAuth;

class Flickr extends AbstractAdapter
{
    protected $requestTokenUrl = "http://www.flickr.com/services/oauth/request_token";

    protected $authorizeUrl = "http://www.flickr.com/services/oauth/authorize";

    protected $accessTokenUrl = "http://www.flickr.com/services/oauth/access_token";

    protected $defaultOptions = array(
        'requestScheme' => OAuth::REQUEST_SCHEME_QUERYSTRING,
        'requestMethod' => OAuth::GET,
    );
}
