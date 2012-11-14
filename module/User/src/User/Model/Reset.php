<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Reset extends AbstractModel
{
    protected $itemClass = 'User\Item\User';

    public function resetRequest(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        $item->self(array('*'));

        $this->trigger('resetrequest.pre');

        $codeItem = $this->getItem('User\Item\Code');
        $codeItem->user_id = $item->id;
        $codeItem->codeType = 'resetPassword';
        $codeItem->setSalt($item->salt);
        $codeItem->create();

        $this->trigger('resetrequest');
    
        $this->trigger('resetrequest.post');

        return $codeItem;
    }

    public function checkRequestCode($code)
    {
    
    }

    public function resetProcess()
    {
    
    }
}
