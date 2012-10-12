<?php
    
namespace Oauth\Adapter\Oauth1;

use Oauth\Adapter\Oauth1\AbstractAdapter;


class Twitter extends AbstractAdapter
{
    protected $requestTokenUrl = "https://api.twitter.com/oauth/request_token";

    protected $authorizeUrl = "https://api.twitter.com/oauth/authorize";

    protected $accessTokenUrl = "https://api.twitter.com/oauth/access_token";
}
