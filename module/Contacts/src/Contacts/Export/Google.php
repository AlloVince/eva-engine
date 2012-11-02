<?php
    
namespace Contacts\Export;

use Contacts\Export\AbstractAdapter;

class Google extends AbstractAdapter
{
    protected $requestUrl = "https://www.google.com/m8/feeds/contacts/default/full?oauth_token=";

    protected function getContactsFromResponse()
    {
        if (!$this->response) {
            return false;
        }
    
        $data = $this->response->getBody();
    
        if (!$data) {
            return false;
        }
    
        $xml=  new \SimpleXMLElement($data);  
        
        $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');  
        $emails = $xml->xpath('//gd:email');  

        $xml = (array) $xml; 
        
        if (!isset($xml['entry'])) {
            return false; 
        }

        foreach ($emails as $key=>$email) {  
            $contacts[] = array(
                'name'  => (string) $xml['entry'][$key]->title,
                'email' => (string) $email->attributes()->address,
            );
        }  
        
        return $contacts; 
    }
}
