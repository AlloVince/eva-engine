<?php
    
namespace Webservice\Adapter\User;

use Webservice\Exception;

class Oauth2Weibo extends AbstractUser
{
    protected $apiMapping = array(
        'UserApi' => array(
            'api' => '/2/users/show.json',
            'beforeCallback' => 'replaceUserId',
        ),
    );

    protected $dataMapping = array(
        'User' => array(
            'Config' => 'UserApi',
            'Type' => 'Read',
            'Nodes' => array(
                'id' => 'id',
                'userName' => 'name',
                'screenName' => 'screen_name',
            ),
        ),
        'Profile' => array(
            'Config' => 'UserApi',
            'Type' => 'Read',
            'Nodes' => array(
                'city' => 'city',
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
            'uid' => $this->userId
        );
        return $params;
    }
}

