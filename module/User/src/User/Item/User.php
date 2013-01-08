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
        'FriendsCount' => array(
            'targetEntity' => 'User\Item\Friend',
            'relationship' => 'OneToMany',
            'joinColumn' => 'user_id',
            'referencedColumn' => 'id',
            'asCount' => true,
            'countKey' => 'friendsCount',
            'joinParameters' => array(
                'count' => true,
            ),
        ),
        'MyFriends' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Friends',
            'joinColumns' => array(
                'joinColumn' => 'user_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'User\Item\Friend',
            'inversedMappedBy' => 'Relation',
            'inverseJoinColumns' => array(
                'joinColumn' => 'friend_id',
                'referencedColumn' => 'id',
            ),
        ),
    );

    protected $inverseRelationships = array(
        'Avatar' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'avatar_id',
            'joinParameters' => array(
            ),
        ),
        'Header' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Header',
            'joinColumns' => array(
                'joinColumn' => 'user_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'User\Item\ImageUser',
            'inversedMappedBy' => 'ImageUserHeader',
            'inverseJoinColumns' => array(
                'joinColumn' => 'file_id',
                'referencedColumn' => 'id',
            ),
            'inverseJoinParameters' => array(
                'usage' => 'header',
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

    public function getEmailHash()
    {
        if($this->email){
            return $this->EmailHash = md5($this->email);
        }
    }
}
