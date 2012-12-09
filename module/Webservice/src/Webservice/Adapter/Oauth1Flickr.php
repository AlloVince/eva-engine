<?php
    
namespace Webservice\Adapter;


class Oauth1Flickr extends AbstractAdapter
{
    protected $successResponseFormat = AbstractAdapter::FORMAT_XML;

    protected $errorResponseFormat = AbstractAdapter::FORMAT_XML;

    protected $authorityType = 'Oauth1';

    protected $authorityClass = 'Oauth\Adapter\Oauth1\Flickr';

    protected $apiHost = 'http://api.flickr.com';

    protected $apiMap = array(
    );
}
