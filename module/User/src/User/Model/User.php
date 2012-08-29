<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModelService;

class User extends AbstractModelService
{
    public function createUser()
    {
        $item = $this->getItem();
        $itemId = $item->create();

        if($item->hasRelationships()){
            foreach($item->getRelationships() as $key => $connectItem){
                //$connectItem->create();
            }
        }

    
    }

    public function saveUser()
    {
    }

    public function removeUser()
    {
    
    }

    public function getUser()
    {
    }

    public function getUserList()
    {
    }
}
