<?php

namespace User\PrivacyAssert;

use Zend\Permissions\Rbac\AssertionInterface;
use Zend\Permissions\Rbac\Rbac;
use Eva\Api;

class AssertMyFriend extends AbstractAssertVisitor implements AssertionInterface
{
    public function assert(Rbac $rbac)
    {
        if(!$this->user || !$this->visitor){
            return false;
        }

        $friendship = \User\Model\Friend::checkFriendship($this->user['id'], $this->visitor['id']);

        return $friendship && $friendship['relationshipStatus'] == 'approved' ? true : false;
    }
}
