<?php
    
namespace Webservice\Adapter\User;

use Webservice\Exception;

class Oauth1Twitter extends AbstractUser
{
    protected $apiMapping = array(
        'UserApi' => array(
            'api' => '/1.1/users/show.json',
            'beforeCallback' => 'replaceUserId',
        ),
    );

    protected $dataMapping = array(
        'User' => array(
            'Config' => 'UserApi',
            'Type' => 'Read',
            'Nodes' => array(
                'id' => 'id',
                'userName' => 'screen_name',
                'screenName' => 'name',
            ),
        ),
        'Profile' => array(
            'Config' => 'UserApi',
            'Type' => 'Read',
            'Nodes' => array(
                'location' => 'location',
                'bio' => 'description',
            ),
            'Callback' => ''
        ),
        'Avatar' => array(
            'Config' => 'UserApi',
            'Type' => 'Read',
            'Nodes' => array(
                'url' => 'profile_image_url',
            ),
        ),
    );

    protected function replaceUserId($params)
    {
        $params['requestParams'] = array(
            'user_id' => $this->userId
        );
        return $params;
    }
}

