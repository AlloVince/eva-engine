<?php

namespace User\PrivacyAssert;

use Zend\Permissions\Rbac\AssertionInterface;
use Zend\Permissions\Rbac\Rbac;
use Eva\Api;

abstract class AbstractAssertVisitor
{
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
}
