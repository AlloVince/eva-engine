<?php
    
namespace Webservice\Adapter;


class Oauth2Douban extends AbstractAdapter
{
    protected $authority;

    protected $successResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $errorResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $authorityType = 'Oauth2';
    protected $authorityClass = 'Oauth\Adapter\Oauth2\Douban';
}
