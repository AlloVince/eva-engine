<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Friend extends AbstractModel
{

    public function requestFriend()
    {
    
    }

    public function approvalFriend()
    {
    
    }

    public function refuseFriend()
    {
    
    }

    public function blockFriend()
    {
    
    }

    public function createFriend($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $fromId = $item->from_user_id;
        $toId = $item->to_user_id;

        if($fromId != $toId) {
            $item->self(array('*'));

            //No relationship, insert
            if(!$item->relationshipStatus){
                $item->from_user_id = $fromId;
                $item->to_user_id = $toId;
                $item->getApprovalTime();
                $item->relationshipStatus = 'approved';
                $item->create();
                $item->self(array('*'));
            }


            //Already have relationship, update to approved
            if($item->relationshipStatus != 'approved'){
                $item->from_user_id = $fromId;
                $item->to_user_id = $toId;
                $item->getApprovalTime();
                $item->relationshipStatus = 'approved';
                $item->save();
            }
        }

        $this->trigger('create');

        $friendItem = clone $item;
        $friendItem->setDataSource(array());
        $friendItem->from_user_id = $toId;
        $friendItem->to_user_id = $fromId;
        if($fromId != $toId) {
            $friendItem->self(array('*'));

            if(!$friendItem->relationshipStatus){
                $friendItem->from_user_id = $toId;
                $friendItem->to_user_id = $fromId;
                $friendItem->getApprovalTime();
                $friendItem->relationshipStatus = 'approved';
                $friendItem->create();
                $friendItem->self(array('*'));
            }

            //Already have relationship, update to approved
            if($friendItem->relationshipStatus != 'approved'){
                $friendItem->from_user_id = $toId;
                $friendItem->to_user_id = $fromId;
                $friendItem->getApprovalTime();
                $friendItem->relationshipStatus = 'approved';
                $friendItem->save();
            }

        }

        $this->trigger('create.post');

        return true;
    }

    public function removeFriend()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $fromId = $item->from_user_id;
        $toId = $item->to_user_id;

        $item->remove();

        $item->from_user_id = $toId;
        $item->to_user_id = $fromId;
        $item->remove();

        $this->trigger('remove');

        $this->trigger('remove.post');

        return true;
    }


    public function getFriendList(array $itemListParameters = array(), $map = null)
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
