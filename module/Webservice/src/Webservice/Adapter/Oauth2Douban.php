<?php
    
namespace Webservice\Adapter;


class Oauth2Douban extends AbstractAdapter
{
    protected $authority;

    protected $successResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $errorResponseFormat = AbstractAdapter::FORMAT_JSON;

}
