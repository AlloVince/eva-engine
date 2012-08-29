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
            //'inversedBy' => 'User\Item\Profile',
        ),
        'Account' => array(
            'targetEntity' => 'User\Item\Account',
            'relationship' => 'OneToOne',
        )
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
