<?php

namespace Blog\Model\Post;

use Eva\Mvc\Model\AbstractItem;

class Item extends AbstractItem
{
    public function getCreateTime()
    {
    }

    public function getUpdateTime()
    {
    }

    public function getUserId()
    {
    }

    public function getUsername()
    {
    }

    public function getUrlname()
    {
    }

    public function getUrl($urlName)
    {
        return '/blog/post/' . $urlName;
    }

    public function getContentHtml($content)
    {
    }


}
