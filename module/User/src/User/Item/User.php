<?php

namespace User\Item;

use Eva\Mvc\Item\AbstractItem;

class User extends AbstractItem
{
    protected $dataSourceClass = 'User\DbTable\Users';

    protected $relationships = array(
        'Profile' => array(
            'targetEntity' => 'User\Item\Profile',
            'relationship' => 'OneToOne',
            'mappedBy' => 'UserProfile',
        ),
        'Account' => array(
            'targetEntity' => 'User\Item\Account',
            'relationship' => 'OneToOne',
        ),
        'FriendsWithMe' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'ManyToMany',
            'inversedBy' => 'User\Item\Friend',
            'joinColumns' => array(
                'joinColumn' => 'to_user_id',
                'referencedColumn' => 'id',
            ),
            'inverseJoinColumns' => array(
                'joinColumn' => 'from_user_id',
                'referencedColumn' => 'id',
            ),
        ),
        'MyFriend' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'ManyToMany',
            'inversedBy' => 'User\Item\Friend',
            'joinColumns' => array(
                'joinColumn' => 'from_user_id',
                'referencedColumn' => 'id',
            ),
            'inverseJoinColumns' => array(
                'joinColumn' => 'to_user_id',
                'referencedColumn' => 'id',
            ),
        ),
    );

    protected $map = array(
        'create' => array(
            'getSalt',
            'getPassword',
        ),
    );

    public function getRegisterTime()
    {
        if(!$this->registerTime){
            return \Eva\Date\Date::getNow();
        }
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
