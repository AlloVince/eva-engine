<?php

namespace Blog\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Category extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Categories';

    protected $events = array(
    );

    
    protected $treeConfig = array(
        'adapter'   => 'BinaryTreeDb',
        'direction' => false,
        'options'   => array(
           'dbTable' => 'Blog\DbTable\Categories',
        ),
    );

    public function createCategory()
    {
        $item = $this->setItemAttrMap(array(
            'urlName' => array('urlName', 'getUrlName'),
            'createTime' => array('createTime', 'getCreateTime'),
        ))->getItemArray();

        $tree = new \Core\Tree\Tree($this->treeConfig['adapter'], $this->treeConfig['direction'], $this->treeConfig['options']);

        $itemId = $tree->insertNode($item);

        if($itemId){
            $item['id'] = $itemId;
            $this->item = $item;
        }

        return $itemId;
    }

    public function saveCategory()
    {
        $item = $this->setItemAttrMap(array(
            'urlName' => array('urlName', 'getUrlName'),
        ))->getItemArray();
        
        $tree = new \Core\Tree\Tree($this->treeConfig['adapter'], $this->treeConfig['direction'], $this->treeConfig['options']);
        $itemId = $tree->updateNode($item);

        if($itemId){
            $item['id'] = $itemId;
            $this->item = $item;
        }

        return $itemId;
    }
    
    public function deleteCategory()
    {
        $item = $this->getItemArray();
        
        $tree = new \Core\Tree\Tree($this->treeConfig['adapter'], $this->treeConfig['direction'], $this->treeConfig['options']);
        $itemId = $tree->deleteNode($item);

        if($itemId){
            $item['id'] = $itemId;
            $this->item = $item;
        }

        return $itemId;
    }
    
    public function getCategory()
    {
        $params = $this->getItemParams();
        
        if(!$params || !(is_numeric($params) || is_string($params))){
            throw new \Core\Model\Exception\InvalidArgumentException(sprintf(
                '%s params %s not correct',
                __METHOD__,
                $params
            ));
        }

        $itemTable = $this->getItemTable();

        if(is_numeric($params)){
            $this->item = $category = $itemTable->where(array('id' => $params))->find('one');
        } else {
            $this->item = $category = $itemTable->where(array('urlName' => $params))->find('one');
        }

        return $this->item = $category;
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
/*
        $itemTable = $this->getItemTable();

        $itemTable->selectCategories($params);
        $categories = $itemTable->find('all');
 */
        $tree = new \Core\Tree\Tree($this->treeConfig['adapter'], $this->treeConfig['direction'], $this->treeConfig['options']);
        $categories = $tree->getTree(); 

        return $this->itemList = $categories;
    }
}
