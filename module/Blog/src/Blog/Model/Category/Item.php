<?php

namespace Blog\Model\Category;

use Eva\Mvc\Model\AbstractItem,
    Eva\Api;

class Item extends AbstractItem
{
    protected $date;

    public function getCreateTime()
    {
        return \Eva\Date\Date::getNow();
    }

    public function getUpdateTime()
    {
        return \Eva\Date\Date::getNow();
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

    public function getFileCategory()
    {
        $subTable = Api::_()->getDbTable('File\DbTable\FilesCategories');
        $item = $this->item;
        $res = $subTable->where(array('category_id' => $item['id']))->find("one");
        if(!$res){
            return array();
        }
        return $res;
    }

    public function getFile()
    {
        $subTable = Api::_()->getDbTable('File\DbTable\Files');
        $item = $this->item;
        
        if (!$item['FileCategory']) {
            return array();
        }

        $res = $subTable->where(array('id' => $item['FileCategory']['file_id']))->find("one");
        if(!$res){
            return array();
        }
        return $res;
    }
}
