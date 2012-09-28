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
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
        ),
        'Account' => array(
            'targetEntity' => 'User\Item\Account',
            'relationship' => 'OneToOne',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
        ),
        'Oauth' => array(
            'targetEntity' => 'User\Item\Oauth',
            'relationship' => 'OneToMany',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
                'columns' => array('user_id', 'appType', 'token', 'tokenSecret'),
                'limit' => false,
            ),
        ),
        'Roles' => array(
            'targetEntity' => 'User\Item\Role',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Roles',
            'joinColumns' => array(
                'joinColumn' => 'user_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'User\Item\RoleUser',
            'inversedMappedBy' => 'RoleUser',
            'inverseJoinColumns' => array(
                'joinColumn' => 'role_id',
                'referencedColumn' => 'id',
            ),
        ),
        'RoleUser' => array(
            'targetEntity' => 'User\Item\RoleUser',
            'relationship' => 'OneToMany',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
        'UserCommonFields' => array(
            'targetEntity' => 'User\Item\Fieldvalue',
            'relationship' => 'OneToMany',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
        'UserRoleFields' => array(
            'targetEntity' => 'User\Item\Fieldvalue',
            'relationship' => 'OneToMany',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
        'MyFriends' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Friends',
            'joinColumns' => array(
                'joinColumn' => 'from_user_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'User\Item\Friend',
            'inversedMappedBy' => 'Relation',
            'inverseJoinColumns' => array(
                'joinColumn' => 'to_user_id',
                'referencedColumn' => 'id',
            ),
        ),
    );

    protected $map = array(
        'create' => array(
            'getSalt()',
            'getPassword()',
            'getOnlineStatus()',
            'getRegisterTime()',
            'getRegisterIp()',
        ),
    );

    public function getOnlineStatus()
    {
        if(!$this->onlineStatus){
            return $this->onlineStatus = 'offline';
        }
    }

    public function getRegisterTime()
    {
        if(!$this->registerTime){
            return $this->registerTime = \Eva\Date\Date::getNow();
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
