<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Privacy extends AbstractModel
{
    protected $itemClass = 'User\Item\Privacysetting';

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
