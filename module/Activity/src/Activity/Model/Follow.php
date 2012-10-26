<?php

namespace Activity\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Follow extends AbstractModel
{
    protected $itemClass = 'Activity\Item\Follower';

    public function followUser($data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('create.pre');

        $itemId = $item->create();

        $this->trigger('create');
    
        $this->trigger('create.post');

        return $itemId;
    }


    public function unfollowUser()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    
    }


}
