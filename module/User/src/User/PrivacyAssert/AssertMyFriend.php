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
        /*
        $friendModel = Api::_()->getModel('User\Model\Friend');
        $friendDb = $friendModel->getItem()->getDataClass();
        $res = $friendDb->where(array(
            'user_id' => $this->user['id'],
            'friend_id' => $this->visitor['id'],
            'relationshipStatus' => 'approved',
        ))->find('one');
        */
        return $friendship && $friendship['relationshipStatus'] == 'approved' ? true : false;
    }
}
