<?php
    
namespace Contacts\Import;

use Contacts\Import\AbstractAdapter;
use Zend\Json\Json;
use Zend\Http\Client;

class Msn extends AbstractAdapter
{
    protected $requestUrl = "https://apis.live.net/V5.0/me/contacts?access_token=";

    /*
    public function getHttpClient()
    {
        if ($this->httpClient) {
            return $this->httpClient;
        }

        $client = new Client();
        $client->setUri('https://livecontacts.services.live.com/users/@C@{0}/rest/livecontacts');
        $client->setOptions(array(
            'maxredirects' => 0,
            'sslverifypeer' => false,
            'timeout'      => 30
        ));

        $headers = $client->getRequest()->getHeaders();
        $headers->addHeaderLine('Authorization', "DelegatedToken dt=\"" . $this->accessToken . "\"");
        $client->send(); 
        
        return $this->httpClient = $client;
    }*/

    protected function getContactsFromResponse()
    {
        if (!$this->response) {
            return false;
        }
        
        $data = $this->response->getBody();
    
        if (!$data) {
            return false;
        }
        
        $data = Json::decode($data);
        p($data);exit;
        exit;
    }
}
