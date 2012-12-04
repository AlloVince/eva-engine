<?php
    
namespace Webservice\Adapter\Feed;

use Webservice\Exception;

class Oauth2Weibo extends AbstractFeed
{
    protected $apiMapping = array(
        'CreateFeedApi' => array(
            'api' => '/2/statuses/update.json',
            'method' => 'POST',
        ),
    );

    protected $dataMapping = array(
        'CreateFeed' => array(
            'Config' => 'CreateFeedApi',
            'Type' => 'Write',
            'Nodes' => array(
                'content' => 'status',
                'latitude' => 'lat',
                'longitude' => 'long',
                'remoteReferenceId' => 'in_reply_to_status_id',
                'geocode' => 'place_id',
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
}

