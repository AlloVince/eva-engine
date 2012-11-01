<?php

namespace Oauth\Item;

use Eva\Mvc\Item\AbstractItem;

class Accesstoken extends AbstractItem
{
    protected $dataSourceClass = 'Oauth\DbTable\Accesstokens';

    protected $relationships = array(
    );

    protected $map = array(
        'create' => array(
            'getUserId()',
        ),
        'save' => array(
            'getUserId()',
        ),
    );

    public function getUserId()
    {
        if(!$this->user_id){
            $user = $this->getModel()->getUser();
            return $this->user_id = $user['id'];
        }
    }
}
