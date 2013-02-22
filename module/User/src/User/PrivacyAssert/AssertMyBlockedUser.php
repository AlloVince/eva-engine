<?php

namespace User\PrivacyAssert;

use Zend\Permissions\Rbac\AssertionInterface;
use Zend\Permissions\Rbac\Rbac;

class AssertMyBlockedUser extends AbstractAssertVisitor implements AssertionInterface
{
    public function assert(Rbac $rbac)
    {
        $friendModel = Api::_()->getModel('User\Model\Friend');
        $friendDb = $friendModel->getItem()->getDataClass();
        $res = $friendDb->where(array(
            'user_id' => $this->user['id'],
            'friend_id' => $this->visitor['id'],
            'relationshipStatus' => 'blocked',
        ))->find('one');
        return true;
    }
}
