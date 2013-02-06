<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Privacy extends AbstractModel
{
    protected $itemClass = 'User\Item\Privacysetting';

    public static $privacyRoles = array(
        'myGuest' => array(
            'roleKey' => 'myGuest',
            'roleName' => 'All User',
        ),
        'myFriend' => array(
            'roleKey' => 'myFriend',
            'roleName' => 'My Friend',
        ),
        /*
        'myFriendOfFriend' => array(
            'roleKey' => 'myFriendOfFriend',
            'roleName' => 'Friend Of My Friend',
        ),
        */
        'onlyMe' => array(
            'roleKey' => 'onlyMe',
            'roleName' => 'Only Me',
        ),
        /*
        'myBlockedUser' => array(
            'roleKey' => 'myBlockedUser',
            'roleName' => 'Blocked User',
        ),
        */
    );

    protected $user;

    protected $visitor;

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setVisitor($visitor)
    {
        $this->visitor = $visitor;
        return $this;
    }

    public function getVisitor()
    {
        return $this->visitor;
    }

    public function getVisitorPrivacyRole()
    {
    
    }

    public function getPrivacy($userId)
    {
        $this->trigger('get.precache');

        $this->setItem(array(
            'user_id' => $userId,
        ));
        $this->trigger('get.pre');

        $item = $this->getItem()->self(array('*'));
        if($item){
            $item = $item->toArray();
            $item = \Zend\Json\Json::decode($item['setting'], \Zend\Json\Json::TYPE_ARRAY);
        }

        $this->trigger('get');

        $this->trigger('get.post');
        $this->trigger('get.postcache');

        return $item;
    }

    public function savePrivacy($data = null)
    {
        $this->trigger('save.pre');

        $item = clone $this->getItem();
        $item->remove();
        $item->create();

        $this->trigger('save');
    
        $this->trigger('save.post');
    }
}
