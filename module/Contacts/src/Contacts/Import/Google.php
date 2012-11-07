<?php
    
namespace Contacts\Import;

use Contacts\Import\AbstractAdapter;
use Zend\Json\Json;

class Google extends AbstractAdapter
{
    protected $requestUrl = "https://www.google.com/m8/feeds/contacts/default/full?max-results=1000&alt=json&oauth_token=";

    protected function getContactsFromResponse()
    {
        if (!$this->response) {
            return false;
        }
    
        $data = $this->response->getBody();
    
        if (!$data) {
            return false;
        }
        $data = Json::decode($data,1);
        
        if (!isset($data['feed']['entry'])) {
            return false; 
        }

        $users = $data['feed']['entry'];

        foreach ($users as $key=>$user) {  
            if (!isset($user['gd$email'])) {
                continue;
            }
            
            $email = null;

            foreach ($user['gd$email'] as $address) {
                if ($email) {
                    continue;
                }
                $email = $address['address'];
            }
            
            if (!$email) {
                continue;
            }

            $contacts[] = array(
                'name'  => isset($user['title']['$t']) ? $user['title']['$t'] : null,
                'email' => $email,
            );
        }  
        
        return $contacts; 
    }
}
