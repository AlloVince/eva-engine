<?php
    
namespace Webservice\Adapter;


class Oauth2Douban extends AbstractAdapter
{
    protected $authority;

    protected $successResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $errorResponseFormat = AbstractAdapter::FORMAT_JSON;

    protected $authorityType = 'Oauth2';
    protected $authorityClass = 'Oauth\Adapter\Oauth2\Douban';


    protected $apiHost = 'https://api.douban.com';
    protected $apiMap = array(
        'Book' => array(
            'getBook' => '/v2/book/:id',
            'addReview' => array(
                'url' => '/v2/book/reviews',
                'method' => 'POST'
            ),
        ),
        'User' => array(
            'getMe' => '/v2/user/~me', 
            'getUser' => '/v2/user/:name', 
            'searchUser' => '/v2/user', 
        ),
    );
}
