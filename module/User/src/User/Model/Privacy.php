<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Privacy extends AbstractModel
{
    protected $itemClass = 'User\Item\Privacysetting';

    public function getPrivacy($userId)
    {
        $this->trigger('get.precache');

        $this->setItem(array(
            'user_id' => $userId,
        ));
        $this->trigger('get.pre');

        $item = $this->getItem()->self(array('*'));
        if($item){
            $item = $item->toArray();
            $item = \Zend\Json\Json::decode($item['setting'], \Zend\Json\Json::TYPE_ARRAY);
        }

        $this->trigger('get');

        $this->trigger('get.post');
        $this->trigger('get.postcache');

        return $item;
    }

    public function savePrivacy($data = null)
    {
        $this->trigger('save.pre');

        $item = clone $this->getItem();
        $item->remove();
        $item->create();

        $this->trigger('save');
    
        $this->trigger('save.post');
    }
}
