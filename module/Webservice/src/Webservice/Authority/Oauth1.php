<?php
    
namespace Webservice\Authority;

use Oauth\OauthService;

class Oauth1 implements AuthorityInterface
{

    protected $options;
    protected $authorityClass;
    protected $serviceLocator;

    public function getClient()
    {
        $oauth = new OauthService();
        $oauth->setServiceLocator($this->serviceLocator);
        $oauth->initByAccessToken($this->options);
        $adapter = $oauth->getAdapter();

        $client = $adapter->getHttpClient();
        return $client;
    }

    public function __construct($authorityClass, $options, $serviceLocator = null)
    {
        $this->authorityClass = $authorityClass;
        $this->options = $options;
        $this->serviceLocator = $serviceLocator;
    }

}
