<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class Field extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Fields';

    protected $relationships = array(
        /*
        'Profile' => array(
            'targetEntity' => 'Field\Item\Profile',
            'relationship' => 'ManyToOne',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
        ),
        'Account' => array(
            'targetEntity' => 'Field\Item\Account',
            'relationship' => 'OneToOne',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
        ),
        'Oauth' => array(
            'targetEntity' => 'Field\Item\Oauth',
            'relationship' => 'OneToMany',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
                'columns' => array('user_id', 'appType', 'token', 'tokenSecret'),
                'limit' => false,
            ),
        ),
        'FriendsWithMe' => array(
            'targetEntity' => 'Field\Item\Field',
            'relationship' => 'ManyToMany',
            'inversedBy' => 'Field\Item\Friend',
            'joinColumns' => array(
                'joinColumn' => 'to_user_id',
                'referencedColumn' => 'id',
            ),
            'inverseJoinColumns' => array(
                'joinColumn' => 'from_user_id',
                'referencedColumn' => 'id',
            ),
        ),
        'MyFriends' => array(
            'targetEntity' => 'Field\Item\Field',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Friends',
            'joinColumns' => array(
                'joinColumn' => 'from_user_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Field\Item\Friend',
            'inversedMappedBy' => 'Relation',
            'inverseJoinColumns' => array(
                'joinColumn' => 'to_user_id',
                'referencedColumn' => 'id',
            ),
        ),
        */
    );

    protected $map = array(
        'create' => array(
        ),
    );

    public function getRegisterTime()
    {
        if(!$this->registerTime){
            return \Eva\Date\Date::getNow();
        }
    }

    public function getRegisterIp()
    {
        return $this->registerIp = $_SERVER["REMOTE_ADDR"];
    }

    public function getSalt()
    {
        $saltArray = \Eva\Stdlib\String\Hash::uniqueHash(true);
        array_pop($saltArray);
        return $this->salt ? $this->salt : $this->salt = implode('', $saltArray);
    }

    public function getPassword()
    {
        if(!$this->password){
            return null;
        }
        $salt = $this->getSalt();

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $bcrypt->setSalt($salt);

        return $this->password = $bcrypt->create($this->password);
    }
}
