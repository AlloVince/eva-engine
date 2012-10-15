<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;

class Kaixin extends AbstractAdapter
{
    protected $authorizeUrl = "http://api.kaixin001.com/oauth2/authorize";
    protected $accessTokenUrl = "https://api.kaixin001.com/oauth2/access_token";
}
