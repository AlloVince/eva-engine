<?php

namespace Video\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Video extends AbstractModel
{
    protected $itemClass = 'Video\Item\Message';

    public function getVideo($idOrUrlName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($idOrUrlName)){
            $this->setItem(array(
                'id' => $idOrUrlName,
            ));
        } elseif(is_string($postIdOrUrlName)) {
            $item = $this->getItem()->getDataClass()->columns(array('id'))->where(array(
                'messageHash' => $idOrUrlName
            ))->find('one');
            if($item){
                $this->setItem(array(
                    'id' => $item['id'],
                ));
            }
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

    public function getVideoList(array $map = array())
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

    public function createVideo($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('create.pre');

        $itemId = $item->create();

        $referenceItem = $this->getItem('Video\Item\Reference');
        $connectVideo = array();
        if($item->reference_id && $item->messageType != 'original'){
            $activityModel = clone $this;
            $connectVideo = $activityModel->getVideo($item->reference_id);
            if($connectVideo){
                $item->reference_id = $connectVideo->id;
                $item->reference_user_id = $connectVideo->user_id;
            }

            if($connectVideo->root_user_id) {
                $item->root_user_id = $connectVideo->root_user_id;
                $item->root_id = $connectVideo->root_id;
            } else {
                $item->root_user_id = $connectVideo->user_id;
                $item->root_id = $connectVideo->id;
            }
        }

        $parser = $item->getParser();
        $userNames = $parser->getUserNames();
        if($userNames){
            $userModel = \Eva\Api::_()->getModel('User\Model\User');
            $atuserItem = $this->getItem('Video\Item\Atuser');
            foreach($userNames as $userName){
                $user = $userModel->getUser($userName);
                $atuserItem->user_id = $user->id;
                $atuserItem->message_id = $itemId;
                $atuserItem->messageType = $item->messageType;
                $atuserItem->author_id = $item->user_id;
                if($item->root_user_id){
                    $atuserItem->root_user_id = $item->root_user_id;
                }
                $atuserItem->create();
            }
        }

        /*
        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->create();
            }
        }
        */
        $this->trigger('create');
    
        $this->trigger('create.post');

        return $itemId;
    }

    public function saveVideo($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('save.pre');

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

    public function removeVideo()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    
    }


}
