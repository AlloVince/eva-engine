<?php

namespace Blog\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Category extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Categories';

    protected $events = array(
    );


    public function createCategory()
    {
        $item = $this->setItemAttrMap(array(
            'urlName' => array('urlName', 'getUrlName'),
            'createTime' => array('createTime', 'getCreateTime'),
        ))->getItemArray();

        $itemTable = $this->getItemTable();
        $itemTable->create($item);
        $itemId = $itemTable->getLastInsertValue();

        if($itemId){
            $item['id'] = $itemId;
            $this->item = $item;
        }

        return $itemId;
    }


    public function getCategories()
    {
        $defaultParams = array(
            'enableCount' => true,
            'page' => 1,
            'order' => 'iddesc',
        );
        $params = $this->getItemListParams();
        $params = new \Zend\Stdlib\Parameters(array_merge($defaultParams, $params));

        $itemTable = $this->getItemTable();

        $itemTable->selectCategories($params);
        $categories = $itemTable->find('all');
        //p($itemTable->debug());

        return $this->itemList = $categories;
    }
}
