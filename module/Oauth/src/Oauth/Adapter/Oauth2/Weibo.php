<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\AdapterInterface;
use Oauth\Adapter\Oauth1\AbstractAdapter;


class Weibo extends AbstractAdapter implements AdapterInterface
{
    protected $requestTokenUrl = "https://api.weibo.com/oauth2/authorize";

    protected $authorizeUrl = "https://api.weibo.com/oauth2/authorize";

    protected $accessTokenUrl = "https://api.weibo.com/oauth2/access_token";
}
