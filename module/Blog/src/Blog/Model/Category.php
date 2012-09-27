<?php

namespace Blog\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Category extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Categories';

    protected $treeConfig = array(
        'adapter'   => 'BinaryTreeDb',
        'direction' => false,
        'options'   => array(
           'dbTable' => 'Blog\DbTable\Categories',
        ),
    );

    public function createCategory($data = null)
    {
        return $this->createItem($data);
    }

    public function saveCategory($data = null)
    {
        return $this->saveItem($data);
    }

    public function removeCategory($data = null)
    {
        return $this->removeItem($data);
    }

    public function getCategory($categoryId, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($categoryId)){
            $this->setItem(array(
                'id' => $categoryId,
            ));
        } 
        $this->trigger('get.pre');

        $item = $this->getItem();
        if($map){
            $item = $item->toArray($map);
        } else {
            $item = $item->self(array('*'));
        }

        $this->trigger('get');

        $this->trigger('get.post');
        $this->trigger('get.postcache');

        return $item;
    }

    public function getCategoryList(array $map = array())
    {
        $this->trigger('list.precache');

        $this->trigger('list.pre');

        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        $this->trigger('get');

        $this->trigger('list.post');
        $this->trigger('list.postcache');

        return $item;
    }
}
