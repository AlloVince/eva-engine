<?php
    
namespace Webservice\Adapter;


class Oauth2Google extends AbstractAdapter
{
    protected $successResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $errorResponseFormat = AbstractAdapter::FORMAT_XML;

    protected $authorityType = 'Oauth2';

    protected $authorityClass = 'Oauth\Adapter\Oauth2\Google';

    protected $apiHost = '';

    protected $apiMap = array();
}
