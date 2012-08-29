<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Account extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Accounts';

    protected $relationships = array(
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'OneToOne',
        ),
    );

    protected $map = array(
        'create' => array(
            'getUserId'
        ),
    );

    public function getUserId()
    {
        if(!$this->user_id){
            return $this->user_id = $this->model->getItem()->id;
        }
    }
}
