<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;

class Tencent extends AbstractAdapter
{
    protected $authorizeUrl = "https://graph.renren.com/oauth/authorize";
    protected $accessTokenUrl = "https://graph.renren.com/oauth/token";
}
