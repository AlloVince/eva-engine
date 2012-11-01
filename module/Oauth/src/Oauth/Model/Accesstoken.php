<?php

namespace Oauth\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Accesstoken extends AbstractModel
{
    protected $user;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function bindToken(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $user = $this->getUser();
        if(!isset($user['id']) || !$user['id']){
            throw new \Exception(sprintf(
                'No user found when bind oauth token in %s',
                get_class($this)
            ));
        }

        $item = $this->getItem();
        
        $this->trigger('bind.pre');

        if($item->selfExist(array('*'))){
            $item->save();
        } else {
            $item->create();
        }

        $this->trigger('bind');
    
        $this->trigger('bind.post');

        return $item->id;
    }

    public function unbindToken()
    {
    
    }
}
