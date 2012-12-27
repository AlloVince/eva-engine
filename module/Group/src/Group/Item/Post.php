<?php

namespace Group\Item;

class Post extends \Blog\Item\Post
{
    protected $dataSourceClass = 'Group\DbTable\Posts';
}
