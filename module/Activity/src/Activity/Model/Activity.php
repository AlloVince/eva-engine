<?php

namespace Activity\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Activity extends AbstractModel
{
    protected $itemClass = 'Activity\Item\Message';

    protected $userList;

    public function setUserList($userList)
    {
        $this->userList = $userList;
        return $this;
    }

    public function getUserList(array $map = array())
    {
        if($this->userList){
            return $this->userList;
        }

        $itemList = $this->getItemList();
        $idArray = array();

        if(!$itemList){
            return array();
        }

        foreach($itemList as $item){
            $idArray[] = $item['id'];
        }
        $userModel = Api::_()->getModel('User\Model\User');
        $userList = $userModel->setItemList(array(
            'id' => $idArray
        ))->getUserList($map);
        return $this->userList = $userList;
    }

    public function getUserActivityList($userId)
    {
        $indexItem = $this->getItem('Activity\Item\Index');
        $indexItem->collections(array(
            'user_id' => $userId,
            'order' => 'iddesc',
        ));
        $messageIdArray = array();
        foreach($indexItem as $index){
            $messageIdArray[] = $index['message_id'];
        }
        $this->setItemList(array(
            'id' => $messageIdArray,
            'order' => 'idarray'
        ));
        return $this;
    }

    //public function get

    public function getActivity($idOrUrlName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($idOrUrlName)){
            $this->setItem(array(
                'id' => $idOrUrlName,
            ));
        } elseif(is_string($idOrUrlName)) {
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

    public function getActivityList(array $map = array())
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

    public function createActivity($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('create.pre');

        $connectActivity = array();
        if($item->reference_id && $item->messageType != 'original'){
            $activityModel = clone $this;
            $connectActivity = $activityModel->getActivity($item->reference_id);
            if($connectActivity){
                $item->reference_id = $connectActivity->id;
                $item->reference_user_id = $connectActivity->user_id;
                if($connectActivity->root_user_id) {
                    $item->root_user_id = $connectActivity->root_user_id;
                    $item->root_id = $connectActivity->root_id;
                } else {
                    $item->root_user_id = $connectActivity->user_id;
                    $item->root_id = $connectActivity->id;
                }
            }
        }

        $itemId = $item->create();

        $referenceItem = $this->getItem('Activity\Item\Reference');
        if($connectActivity){
            $referenceItem->user_id = $item->user_id;
            $referenceItem->message_id = $item->id;
            $referenceItem->reference_user_id = $item->reference_user_id;
            $referenceItem->reference_message_id = $item->reference_id;
            $referenceItem->messageType = $item->messageType;
            $referenceItem->createTime = $item->createTime;
            if($item->root_user_id){
                $referenceItem->root_user_id = $item->root_user_id;
                $referenceItem->root_message_id = $item->root_id;
            }
            $referenceItem->create();
        }

        $parser = $item->getParser();
        $userNames = $parser->getUserNames();
        if($userNames){
            $userModel = \Eva\Api::_()->getModel('User\Model\User');
            $atuserItem = $this->getItem('Activity\Item\Atuser');
            foreach($userNames as $userName){
                $user = $userModel->getUser($userName);
                if(!$user) {
                    continue;
                }
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

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->create();
            }
        }
        $this->trigger('create');
    
        $this->trigger('create.post');

        return $itemId;
    }

    public function saveActivity($data = null)
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

    public function removeActivity()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $item->remove();

        $this->trigger('remove');
    
        $this->trigger('remove.post');

        return true;
    
    }


}
