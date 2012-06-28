<?php

namespace Blog\Model\Post;

use Eva\Mvc\Model\AbstractItem;

class Item extends AbstractItem
{
    public function getCreateTime()
    {
        $this->date = $date = new \Eva\Date\Date();
        return $date->get('YYYY-MM-dd HH:mm:ss');
    }

    public function getUpdateTime()
    {
        $date = $this->date ? $this->date : new \Eva\Date\Date();
        return $date->get('YYYY-MM-dd HH:mm:ss');
    }

    public function getUserId()
    {
    }

    public function getUserName()
    {
    }

    public function getUrlName($urlName)
    {
        if($urlName){
            return $urlName;
        }
        return \Eva\Stdlib\String\Hash::uniqueHash();
    }

    public function getUrl($urlName)
    {
        return '/blog/post/' . $urlName;
    }

    public function getContentHtml($content)
    {
    }


}
