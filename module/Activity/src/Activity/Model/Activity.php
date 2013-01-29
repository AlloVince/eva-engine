<?php

namespace Activity\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Activity extends AbstractModel
{
    protected $itemClass = 'Activity\Item\Message';

    protected $userList;

    protected $userActivityPaginator;

    public function getUserActivityPaginator()
    {
        return $this->userActivityPaginator;
    }

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

        foreach($itemList as $item){
            $idArray[] = $item['user_id'];
        }

        $userModel = Api::_()->getModel('User\Model\User');
        if(!$idArray){
            $userModel->setItemList(array(
                'noResult' => true
            ));
        } else {
            $userModel->setItemList(array(
                'id' => $idArray
            ));
        }
        $userList = $userModel->getUserList($map);
        return $this->userList = $userList;
    }

    public function getUserActivityList($params, $onlySelf = false)
    {
        $indexItem = $this->getItem('Activity\Item\Index');

        $defaultParams = array(
            'user_id' => '',
            'author_id' => '',
            'order' => 'iddesc',
            'page' => 1,
            'rows' => 20,
        );

        $itemQueryParams = array_merge($defaultParams, $params);
        if($onlySelf){
            $itemQueryParams['author_id'] = $userId;
        }

        $indexItem->collections($itemQueryParams);
        $this->userActivityPaginator = $indexItem->getPaginator();

        $messageIdArray = array();
        foreach($indexItem as $index){
            $messageIdArray[] = $index['message_id'];
        }
        if(!$messageIdArray){
            $this->setItemList(array(
                'noResult' => true
            ));
        } else {
            $this->setItemList(array(
                'id' => $messageIdArray,
                'order' => 'idarray',
                'noLimit' => true,
            ));
        }
        return $this;
    }

    public function getForwardActivityList()
    {
        $itemList = $this->getItemList();
        $idArray = array();

        if(!$itemList){
            return array();
        }

        foreach($itemList as $item){
            if(!$item['reference_id']){
                continue;
            }
            $idArray[] = $item['reference_id'];
        }
        if($idArray){
            $this->setItemList(array(
                'id' => $idArray
            ));
        } else {
            $this->setItemList(array(
                'noResult' => true
            ));
        }
        return $this;
    }

    public function getCommentActivityList()
    {
        $item = $this->getItem();
        $referenceItem = $this->getItem('Activity\Item\Reference');
        $referenceList = $referenceItem->collections(array(
            'reference_message_id' => $item->id,
            'messageType' => 'comment',
            'order' => 'iddesc',
        ));

        $messageIdArray = array();
        foreach($referenceList as $reference){
            $messageIdArray[] = $reference['message_id'];
        }
        if(!$messageIdArray){
            $this->setItemList(array(
                'noResult' => true
            ));
        } else {
            $this->setItemList(array(
                'id' => $messageIdArray,
                'order' => 'idarray'
            ));
        }
        return $this;
    }


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
            $atuserDataSource = array();
            foreach($userNames as $userName){
                $user = $userModel->getUser($userName);
                if(!$user) {
                    continue;
                }

                /*
                $atuserDataSource[] = array(
                    'user_id' => $user->id,
                    'message_id' => $itemId,
                    'messageType' => $item->messageType,
                    'author_id' => $item->user_id,
                    'root_user_id' => $item->root_user_id ? $item->root_user_id : 0,
                );
                $atuserItem->setDataSource($atuserDataSource);
                foreach($atuserItem as $atuserItemSub){
                    $atuserItemSub->create();
                }
                */

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
