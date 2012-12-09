<?php
    
namespace Webservice\Adapter\User;

use Webservice\Exception;

class Oauth2Douban extends AbstractUser
{
    protected $apiMapping = array(
        'UserApi' => array(
            'api' => '/v2/user/:name',
            'beforeCallback' => 'replaceUserId',
        ),
    );

    protected $dataMapping = array(
        'User' => array(
            'Config' => 'UserApi',
            'Type' => 'Read',
            'Nodes' => array(
                'id' => 'id',
                'userName' => 'uid',
                'screenName' => 'name',
            ),
        ),
        'Profile' => array(
            'Config' => 'UserApi',
            'Type' => 'Read',
            'Nodes' => array(
                'city' => 'loc_name',
                'bio' => 'desc',
            ),
        ),
        'Avatar' => array(
            'Config' => 'UserApi',
            'Type' => 'Read',
            'Nodes' => array(
                'url' => 'avatar',
            ),
        ),
    );

    protected function replaceUserId($params)
    {
        $params['apiParams'] = $this->userId;
        return $params;
    }
}
