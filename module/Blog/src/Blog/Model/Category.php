<?php

namespace Blog\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Category extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Categories';

    protected $data;
    protected $subData;

    protected $user;

    protected $events = array(
    );

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
