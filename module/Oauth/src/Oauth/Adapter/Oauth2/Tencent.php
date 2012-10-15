<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;

class Tencent extends AbstractAdapter
{
    protected $accessTokenFormat = 'pair';
    protected $authorizeUrl = "https://graph.qq.com/oauth2.0/authorize";
    protected $accessTokenUrl = "https://graph.qq.com/oauth2.0/token";
}
