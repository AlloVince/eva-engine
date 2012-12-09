<?php
    
namespace Webservice\Adapter;


class Oauth2Facebook extends AbstractAdapter
{
    protected $successResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $errorResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $authorityType = 'Oauth2';

    protected $authorityClass = 'Oauth\Adapter\Oauth2\Facebook';

    protected $apiHost = 'https://graph.facebook.com';

    protected $apiMap = array(
    );
}
