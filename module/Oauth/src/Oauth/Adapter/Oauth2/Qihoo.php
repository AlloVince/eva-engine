<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;

class Qihoo extends AbstractAdapter
{
    protected $authorizeUrl = "https://openapi.360.cn/oauth2/authorize";
    protected $accessTokenUrl = "https://openapi.360.cn/oauth2/access_token";
}
