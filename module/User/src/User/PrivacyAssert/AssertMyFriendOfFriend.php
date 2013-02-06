<?php

namespace User\PrivacyAssert;

use Zend\Permissions\Rbac\AssertionInterface;
use Zend\Permissions\Rbac\Rbac;

class AssertMyFriendOfFriend extends AbstractAssertVisitor implements AssertionInterface
{
    public function assert(Rbac $rbac)
    {
        return false;
    }
}
