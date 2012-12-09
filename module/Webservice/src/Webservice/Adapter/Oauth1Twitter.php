<?php
    
namespace Webservice\Adapter;


class Oauth1Twitter extends AbstractAdapter
{
    protected $successResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $errorResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $authorityType = 'Oauth1';

    protected $authorityClass = 'Oauth\Adapter\Oauth1\Twitter';

    protected $apiHost = 'https://api.twitter.com';

    protected $apiMap = array(
    );
}
