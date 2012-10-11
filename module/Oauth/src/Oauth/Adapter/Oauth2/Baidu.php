<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\AdapterInterface;
use Oauth\Adapter\Oauth1\AbstractAdapter;


class Baidu extends AbstractAdapter implements AdapterInterface
{
    protected $requestTokenUrl = "https://openapi.baidu.com/oauth/2.0/authorize";

    protected $authorizeUrl = "https://openapi.baidu.com/oauth/2.0/authorize";

    protected $accessTokenUrl = "https://openapi.baidu.com/oauth/2.0/token";
}
