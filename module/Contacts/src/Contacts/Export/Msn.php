<?php
    
namespace Contacts\Export;

use Contacts\Export\AbstractAdapter;
use Zend\Json\Json;

class Msn extends AbstractAdapter
{
    protected $requestUrl = "https://apis.live.net/V5.0/me/contacts?access_token=";

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
