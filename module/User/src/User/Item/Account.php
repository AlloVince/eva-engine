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
            'joinColumn' => 'user_id',
            'referencedColumnName' => 'id',
        ),
    );

    protected $map = array(
    );

    /*
    public function getUserId()
    {
        if(!$this->user_id){
            return $this->user_id = $this->model->getItem()->id;
        }
    }
    */
}
