<?php
    
namespace Webservice\Adapter;


class Oauth2Weibo extends AbstractAdapter
{
    protected $successResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $errorResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $authorityType = 'Oauth2';

    protected $authorityClass = 'Oauth\Adapter\Oauth2\Weibo';

    protected $apiHost = 'https://api.weibo.com';

    protected $apiMap = array(
        'User' => array(
            'getUser' => '/2/users/show.json', 
        ),
    );
}
