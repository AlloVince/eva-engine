<?php
    
namespace Webservice\Authority;

use Oauth\OauthService;

class Oauth2 implements AuthorityInterface
{

    protected $options;
    protected $authorityClass;

    public function getClient()
    {
        $oauth = new OauthService();
        $oauth->initByAccessToken($this->options);
        $adapter = $oauth->getAdapter();

        $client = $adapter->getHttpClient();
        return $client;
    }

    public function __construct($authorityClass, $options)
    {
        $this->authorityClass = $authorityClass;
        $this->options = $options;
    }

}
