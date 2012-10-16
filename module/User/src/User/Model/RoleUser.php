<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class RoleUser extends AbstractModel
{
    public function createRoleUser(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }
        
        $item = $this->getItem();
        
        $itemId = $item->createRoleUser();

        return $itemId;
    }
}
