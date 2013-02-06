<?php

namespace User\PrivacyAssert;

use Zend\Permissions\Rbac\AssertionInterface;
use Zend\Permissions\Rbac\Rbac;
use Eva\Api;

abstract class AbstractAssertVisitor
{
    protected $user;

    protected $visitor;

    protected $relationship = false;

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

    /*
    public function getRelationship()
    {
        if($this->relationship){
            return $this->relationship;
        }

        $friendModel = Api::_()->getModel('User\Model\Friend');
        $friendDb = $friendModel->getItem()->getDataClass();
        $res = $friendDb->where(array(
            'user_id' => $this->user['id'],
            'friend_id' => $this->visitor['id'],
        ))->find('one');
        return $this->relationship = $res;
    }
    */

}
