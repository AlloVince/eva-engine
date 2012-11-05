<?php

namespace Oauth\Item;

use Eva\Mvc\Item\AbstractItem;

class Accesstoken extends AbstractItem
{
    protected $dataSourceClass = 'Oauth\DbTable\Accesstokens';

    protected $relationships = array(
    );

    protected $inverseRelationships = array(
        'Oauth' => array(
            'targetEntity' => 'Oauth\Item\Accesstoken',
            'relationship' => 'OneToMany',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
                'columns' => array('user_id', 'appType', 'token', 'tokenSecret'),
                'limit' => false,
            ),
        ),
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
        //Maybe here will change bind user, so not need to check user_id already exist
        $user = $this->getModel()->getUser();
        return $this->user_id = $user['id'];
    }
}
