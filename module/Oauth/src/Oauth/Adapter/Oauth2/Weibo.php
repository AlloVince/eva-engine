<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;


class Weibo extends AbstractAdapter
{
    protected $authorizeUrl = "https://api.weibo.com/oauth2/authorize";
    protected $accessTokenUrl = "https://api.weibo.com/oauth2/access_token";
}
