<?php

namespace Oauth\Item;

use Eva\Mvc\Item\AbstractItem;

class Accesstoken extends AbstractItem
{
    protected $dataSourceClass = 'Oauth\DbTable\Accesstokens';

    protected $relationships = array(
    );
}
