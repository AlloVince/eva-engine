<?php

namespace User\PrivacyAssert;

use Zend\Permissions\Rbac\AssertionInterface;
use Zend\Permissions\Rbac\Rbac;

class AssertOnlyMe extends AbstractAssertVisitor implements AssertionInterface
{
    public function assert(Rbac $rbac)
    {
        if(!$this->user || !$this->visitor){
            return false;
        }
        return $this->user['id'] == $this->visitor['id'];
    }
}

