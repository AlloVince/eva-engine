<?php
    
namespace Webservice\Adapter\Album;

use Webservice\Exception;

use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part;


class Oauth1Flickr extends AbstractAlbum
{
    protected $apiMapping = array(
        'UploadPhotoApi' => array(
            'api' => '/services/upload/',
            'method' => 'POST',
        ),
    );

    protected $dataMapping = array(
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

    public function uploadPhoto($params)
    {
        $apiParams = $this->prepareApiParamsFromMapping('UploadPhoto');
        $mapping = $this->dataMapping['UploadPhoto'];

        $adapter = $this->getAdapter();

        $client = $adapter->getClient();
        $client->setUri('http://api.flickr.com/services/upload/');
        $client->setMethod($apiParams['method']);
        $client->setParameterGet(array(
            'format' => 'json',
        ));

        $client->setEncType('form-data');
        $client->setFileUpload($params['photo'], 'photo');
        unset($params['photo']);

        $consumerKey = $client->getConsumerKey();
        $consumerSecret = $client->getConsumerSecret();
        $token = $client->getToken()->getParam('oauth_token');

        $params = $adapter->writeMapping($params, $mapping['Nodes']);

        $apiSig = $consumerSecret . 'api_key' . $consumerKey . 'auth_token' . $token;
        $apiSig = md5($apiSig);

        $publicParams = array(
            'api_key' => $consumerKey,
            'auth_token' => $token,
            'api_sig' => $apiSig,
        );

        $client->setParameterPost(array_merge($publicParams, $params));

        $adapter->setClient($client);
        $adapter->sendApiRequest();
        $data = $adapter->getApiData();

        $data = $adapter->readMapping($data, $mapping['ResponseNodes']);
        return $data;
    }
}

