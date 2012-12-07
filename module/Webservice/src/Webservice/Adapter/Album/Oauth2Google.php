<?php
    
namespace Webservice\Adapter\Album;

use Webservice\Exception;

use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part;


class Oauth2Google extends AbstractAlbum
{
    protected $apiMapping = array(
        'UploadPhotoApi' => array(
            'api' => 'https://picasaweb.google.com/data/feed/api/user/:user_id/albumid/104171418568283484752',
            'method' => 'POST',
        ),
        'AlbumListApi' => array(
            'api' => 'https://picasaweb.google.com/data/feed/api/user/default',
        )
    );

    protected $dataMapping = array(
        'AlbumList' => array(
            'Config' => 'AlbumListApi',
            'Type' => 'Read',
            'IsList' => true,
            'ListNodes' => '',
            'Nodes' => array(
                'title' => 'title',
                'description' => 'description',
                'tags' => 'tags',
                'safetyLevel' => 'safety_level',
                'content_type' => 'content_type',
            ),
        ),
        'UploadPhoto' => array(
            'Config' => 'UploadPhotoApi',
            'Type' => 'Write',
            'Nodes' => array(
                'title' => 'title',
                'description' => 'description',
                'tags' => 'tags',
                'safetyLevel' => 'safety_level',
                'content_type' => 'content_type',
            ),
            'ResponseNodes' => array(
                'remoteId' => 'rsp::photoid',
            ),
        ),
    );

    public function getAlbumList()
    {
        $apiParams = $this->prepareApiParamsFromMapping('AlbumList');
        p($apiParams);
    
    }

    public function uploadPhoto($params)
    {
        $apiParams = $this->prepareApiParamsFromMapping('UploadPhoto');
        $mapping = $this->dataMapping['UploadPhoto'];

        $adapter = $this->getAdapter();
        $userId = $this->userId;
        $albumId = $this->albumId;

        $client = $adapter->getClient();

        $service = new \ZendGData\Photos($client);
        $fileSource = $service->newMediaFileSource($params['FullPath']);
        $fileSource->setContentType('image/' . $params['fileExtension']);
        $fileSource->setSlug($params['fileName']);
        $entry = $service->newPhotoEntry();
        $entry->setSummary($service->newSummary($params['description']));
        $entry->setTitle($service->newTitle($params['title']));
        $entry->setMediaSource($fileSource);

        $uri = "https://picasaweb.google.com/data/feed/api/user/$userId/albumid/$albumId";
        $data = $service->prepareRequest('POST', $albumId, null, $entry);

        $body = $data['data']->read($data['data']->getTotalSize());
        $client->setUri($uri);
        $client->setMethod('POST');
        $client->setRawBody($body);
        $client->setHeaders(array_merge(array(
            'GData-Version' => '2',
            'Authorization' => $client->getToken()->getParam('access_token'),
            'Content-Type' => $data['contentType'],
        ), $data['headers']));
        $client->setParameterGet(array(
            'alt' => 'json'
        ));

        $adapter->setClient($client);
        $data = $adapter->getApiData();
        return $data;
    }
}

