<?php
    
namespace Oauth\Adapter\Oauth2;

use Oauth\Adapter\Oauth2\AbstractAdapter;

class Msn extends AbstractAdapter
{
    protected $authorizeUrl = "https://oauth.live.com/authorize";
    protected $accessTokenUrl = "https://oauth.live.com/token";
}
