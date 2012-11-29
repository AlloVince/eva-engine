<?php
    
namespace Webservice\Adapter\User;

use Webservice\Adapter\AbstractUniform;
use Webservice\Exception;

class Oauth2Douban extends AbstractUniform
{
    protected $defaultApi = 'User::getMe';

    protected $mapping = array(
        'User' => array(
            'userName' => 'uid',
            'screenName' => 'name',
            /*
            'email' => array(
                'fromApi' => 'User::getEmail',
                'key' => 'email_list::email',
            ),
            */
        ),
        'Profile' => array(
            'city' => 'loc_name',
            'bio' => 'desc',
        ),
        'Avatar' => array(
            'url' => 'avatar',
        ),
        'Oauth' => array(
            'remoteUserId' => 'id',
        ),
    );

}
