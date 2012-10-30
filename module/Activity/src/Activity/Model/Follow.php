<?php

namespace Activity\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Follow extends AbstractModel
{
    protected $itemClass = 'Activity\Item\Follower';

    protected $userList;

    public function getUserList()
    {
        return $this->userList;
    }

    public function setUserList($userList)
    {
        $this->userList = $userList;
        return $this;
    }

    public function getFollowList(array $itemListParameters = array(), $map = null)
    {
        $this->trigger('list.precache');

        $userList = $this->getUserList();
        if($userList){
            $userIdArray = array();
            foreach($userList as $user) {
                $userIdArray[] = $user['id'];
            }
            if($userIdArray){
                $this->itemListParameters['user_id'] = $userIdArray;
            }
        }

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

    public function followUser($data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $userId = $item->user_id;
        $followerId = $item->follower_id;

        if($userId != $followerId) {
            $item->self(array('*'));

            if(!$item->relationshipStatus){
                $item->user_id = $userId;
                $item->follower_id = $followerId;
                $item->create();
                $item->self(array('*'));
            }
        }

        $this->trigger('create');

        if($userId != $followerId && $item->relationshipStatus == 'single'){
            $fansItem = clone $item;
            $fansItem->setDataSource(array());
            $fansItem->user_id = $item->follower_id;
            $fansItem->follower_id = $item->user_id;

            $fansItem->self(array('*'));

            if($fansItem->relationshipStatus){
                $item->relationshipStatus = 'double';
                $item->save();
                $fansItem->relationshipStatus = 'double';
                $fansItem->save();
            }
        }

        $this->trigger('create.post');

        return true;
    }


    public function unfollowUser()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $userId = $item->user_id;
        $followerId = $item->follower_id;

        $item->remove();

        $this->trigger('remove');

        $fansItem = clone $item;
        $fansItem->setDataSource(array());
        $fansItem->user_id = $followerId;
        $fansItem->follower_id = $userId;
        $fansItem->self(array('*'));

        if($fansItem->relationshipStatus){
            $fansItem->user_id = $followerId;
            $fansItem->follower_id = $userId;
            $fansItem->relationshipStatus = 'single';
            $fansItem->save();
        }

        $this->trigger('remove.post');

        return true;
    }


}
