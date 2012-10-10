<?php
    
namespace Oauth\Adapter\Oauth1;

use Oauth\Adapter\AdapterInterface;
use Oauth\Adapter\Oauth1\AbstractAdapter;


class Douban extends AbstractAdapter implements AdapterInterface
{
    protected $requestTokenUrl = "https://www.douban.com/service/auth/request_token";

    protected $authorizeUrl = "https://www.douban.com/service/auth/authorize";

    protected $accessTokenUrl = "https://www.douban.com/service/auth/access_token";
}
