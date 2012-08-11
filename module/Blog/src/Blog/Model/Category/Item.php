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
        $item = $this->item;
        if (!isset($item['FileCategory']['file_id']) || !$item['FileCategory']['file_id']) {
            return array();
        }
        $subModel = Api::_()->getModel('File\Model\File');
        $res = $subModel->setItemParams($item['FileCategory']['file_id'])->getFile();
        if(!$res){
            return array();
        }
        return $res;
    }
}
