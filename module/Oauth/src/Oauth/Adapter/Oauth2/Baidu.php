<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;

class Baidu extends AbstractAdapter 
{
    protected $authorizeUrl = "https://openapi.baidu.com/oauth/2.0/authorize";
    protected $accessTokenUrl = "https://openapi.baidu.com/oauth/2.0/token";
}
