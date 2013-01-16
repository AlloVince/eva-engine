<?php

namespace Album\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Album extends AbstractModel
{
    protected $itemTableName = 'Album\DbTable\Albums';

    protected $userList;

    public function setUserList($userList)
    {
        $this->userList = $userList;
        return $this;
    }

    public function getUserList($itemParams = array(), array $map = array())
    {
        if($this->userList){
            return $this->userList;
        }

        if(!Api::_()->isModuleLoaded('User')){
            return array();
        }

        $itemList = $this->getItemList();
        $idArray = array();

        foreach($itemList as $item){
            $idArray[] = $item['user_id'];
        }

        $userModel = Api::_()->getModel('User\Model\User');
        if(!$idArray){
            $itemParams['noResult'] = true;
        } else {
            $itemParams['id'] = $idArray;
        }
        $userModel->setItemList($itemParams);
        $userList = $userModel->getUserList($map);
        return $this->userList = $userList;
    }


    public function getAlbum($albumIdOrUrlName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($albumIdOrUrlName)){
            $this->setItem(array(
                'id' => $albumIdOrUrlName,
            ));
        } elseif(is_string($albumIdOrUrlName)) {
            $this->setItem(array(
                'albumKey' => $albumIdOrUrlName,
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

    public function getAlbumList(array $map = array())
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

    public function createAlbum($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $itemId = $item->create();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->create();
            }
        }
        
        $albumCountItem = $this->getItem('Album\Item\Count');
        $albumCountItem->album_id = $itemId;
        $albumCountItem->fileCount  = 0;
        $albumCountItem->create();

        $this->trigger('create');

        $this->trigger('create.post');

        return $itemId;
    }

    public function saveAlbum($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('save.pre');
        
        //Admin save item will remove all categories
        $categoryDb = Api::_()->getDbTable('Album\DbTable\CategoriesAlbums');
        $categoryDb->where(array(
            'album_id' => $item->id,
        ))->remove();

        $item->save();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->save();
            }
        }
        $this->trigger('save');

        $this->trigger('save.post');

        return $item->id;
    }

    public function removeAlbum()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $subItem = $item->join('Count');
        $subItem->remove();

        $subDb =  Api::_()->getDbTable('Album\DbTable\AlbumsFiles');
        $subDb->where(array(
            'album_id' => $item->id,
        ))->remove();

        $item->remove();

        $this->trigger('remove');

        $this->trigger('remove.post');

        return true;

    }


}
