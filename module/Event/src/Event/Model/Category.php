<?php

namespace Event\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Category extends AbstractModel
{
    protected $itemTableName = 'Event\DbTable\Categories';

    protected $treeConfig = array(
        'adapter'   => 'BinaryTreeDb',
        'direction' => false,
        'options'   => array(
           'dbTable' => 'Event\DbTable\Categories',
        ),
    );

    public function createCategory($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $itemId = $item->create();

        $this->trigger('create');

        $this->trigger('create.post');

        return $itemId;
    }

    public function saveCategory($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('save.pre');

        $item->save();

        $this->trigger('save');

        $this->trigger('save.post');

        return $item->id;
    }

    public function removeCategory($data = null)
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $subDb =  Api::_()->getDbTable('Event\DbTable\CategoriesEvents');
        $subDb->where(array(
            'category_id' => $item->id,
        ))->remove();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');
    }

    public function getCategory($categoryId, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($categoryId)){
            $this->setItem(array(
                'id' => $categoryId,
            ));
        }elseif(is_string($categoryId)) {
            $this->setItem(array(
                'urlName' => $categoryId,
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
