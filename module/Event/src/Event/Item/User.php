<?php

namespace Event\Item;

class User extends \User\Item\User
{
    protected $dataSourceClass = 'Event\DbTable\Users';
}
