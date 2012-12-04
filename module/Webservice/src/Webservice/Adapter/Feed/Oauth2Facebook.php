<?php
    
namespace Webservice\Adapter\Feed;

use Webservice\Exception;

class Oauth2Facebook extends AbstractFeed
{
    protected $apiMapping = array(
        'CreateFeedApi' => array(
            'api' => '/:PROFILE_ID/feed',
            'method' => 'POST',
            'beforeCallback' => 'replaceUserId',
            'requiredScopes' => 'publish_actions',
        ),
    );

    protected $dataMapping = array(
        'CreateFeed' => array(
            'Config' => 'CreateFeedApi',
            'Type' => 'Write',
            'Nodes' => array(
                'content' => 'message',
                'geocode' => 'place',
            ),
            'ResponseNodes' => array(
                'remoteId' => 'id',
            ),
        ),
    );

    public function createFeed($params)
    {
        $this->writeData('CreateFeed', $params);
    }

    protected function replaceUserId($params)
    {
        $params['apiParams'] = $this->userId;
        return $params;
    }
}

