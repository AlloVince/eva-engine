<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;

class Github extends AbstractAdapter
{
    protected $authorizeUrl = "https://github.com/login/oauth/authorize";
    protected $accessTokenUrl = "https://github.com/login/oauth/access_token";
}
