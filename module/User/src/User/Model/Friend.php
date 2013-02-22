<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Friend extends AbstractModel
{

    /*
    * Status Change Rules:
    * empty => pending       requestFriend()
    * pending => approved    approveFriend()
    * pending => refused     refuseFriend()
    * approved => removed    unfriend()
    * 
    * anyStatus => blocked    blockFriend()
    * 
    */
    protected $allowStatusChangeRules = array(
        array('pending', 'approved'),
        array('pending', 'refused'),
        array('pending', 'removed'),
        array('approved', 'removed'),
        array('refused', 'pending'),
        array('pending', 'blocked'),
        array('approved', 'blocked'),
        array('refused', 'blocked'),
        array('removed', 'blocked'),
        array('blocked', 'refused'),
    );

    protected static $friendship;

    public static function checkFriendship($userId, $checkUserId)
    {
        $innerCacheKey = "$userId-$checkUserId";
        if(isset(self::$friendship[$innerCacheKey])){
            return self::$friendship[$innerCacheKey];
        }
        $itemModel = clone Api::_()->getModel('User\Model\Friend');
        $item = $itemModel->setItem(array(
            'user_id' => $userId,
            'friend_id' => $checkUserId,
        ))->getItem()->self(array(
            '*'
        ));

        $item = $item ? $item->toArray() : array();
        self::$friendship[$innerCacheKey] = $item;
        return $item;
    }


    public function requestFriend()
    {
        $item = $this->getItem();

        $fromId = $item->user_id;
        $toId = $item->friend_id;
        $requestUserId = $item->request_user_id;

        if($fromId == $toId) {
            return;
        }
        $this->trigger('request.pre');

        $checkItem = clone $item;
        $checkItem->friend_id = $fromId;
        $checkItem->user_id = $toId;

        if($checkItem->self(array('*')) && (
            //Already sent request
            $checkItem->relationshipStatus == 'pending' ||
            //Already been friend
            $checkItem->relationshipStatus == 'approved' ||
            //Be blocked
            $checkItem->relationshipStatus == 'blocked'
        )) {
            return;
        }

        $this->trigger('request');

        //Status is refused or removed
        if($item->relationshipStatus){

            $item->relationshipStatus = 'pending';
            $item->request_user_id = $requestUserId;
            $item->getRequestTime();
            $item->save();

        } else {
            $item->user_id = $fromId;
            $item->friend_id = $toId;
            $item->getRequestTime();
            $item->relationshipStatus = 'pending';
            $item->create();
        }

        $friendItem = clone $item;
        $friendItem->user_id = $toId;
        $friendItem->friend_id = $fromId;
        $friendItem->relationshipStatus = 'pending';
        if($friendItem->selfExist()){
            $friendItem->save();
        } else {
            $friendItem->create();
        }

        $this->trigger('request.post');
    }

    public function approveFriend()
    {
        $this->trigger('approve.pre');

        $item = $this->getItem();
        $item->self(array('*'));
        if(!$item || !$this->isStatusChangeAllow('approved')){
            return;
        }

        $this->trigger('approve');
        $this->updateFriendStatus('approved');
        $this->trigger('approve.post');
    }

    public function refuseFriend()
    {
        $this->trigger('refuse.pre');

        $item = $this->getItem();
        $item->self(array('*'));
        if(!$item || !$this->isStatusChangeAllow('refused')){
            return;
        }

        $this->trigger('refuse');
        $this->updateFriendStatus('refused');
        $this->trigger('refuse.post');
    }

    public function blockFriend()
    {
        $this->trigger('block.pre');

        $item = $this->getItem();
        $item->self(array('*'));
        if(!$item || !$this->isStatusChangeAllow('blocked')){
            return;
        }

        $this->trigger('block');
        $this->updateFriendStatus('blocked', false);
        $this->trigger('block.post');
    }

    public function unFriend()
    {
        $this->trigger('unfriend.pre');

        $item = $this->getItem();
        $item->self(array('*'));
        if(!$item || !$this->isStatusChangeAllow('removed')){
            return;
        }

        $this->trigger('unfriend');
        $this->updateFriendStatus('removed');
        $this->trigger('unfriend.post');
    }

    public function unblockFriend()
    {
        $this->trigger('unblock.pre');

        $item = $this->getItem();
        $item->self(array('*'));
        if(!$item || !$this->isStatusChangeAllow('refused')){
            return;
        }

        $this->trigger('unblock');
        $this->updateFriendStatus('refused', false);
        $this->trigger('unblock.post');
    }

    public function createFriend($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $fromId = $item->user_id;
        $toId = $item->friend_id;

        if($fromId != $toId) {
            $item->self(array('*'));

            //No relationship, insert
            if(!$item->relationshipStatus){
                $item->user_id = $fromId;
                $item->friend_id = $toId;
                //$item->request_user_id = $fromId;
                $item->getApprovalTime();
                $item->relationshipStatus = 'approved';
                $item->create();
                $item->self(array('*'));
            }


            //Already have relationship, update to approved
            if($item->relationshipStatus != 'approved'){
                $item->user_id = $fromId;
                $item->friend_id = $toId;
                $item->getApprovalTime();
                $item->relationshipStatus = 'approved';
                $item->save();
            }
        }

        $this->trigger('create');

        $friendItem = clone $item;
        $friendItem->setDataSource(array());
        $friendItem->user_id = $toId;
        $friendItem->friend_id = $fromId;
        if($fromId != $toId) {
            $friendItem->self(array('*'));

            if(!$friendItem->relationshipStatus){
                $friendItem->user_id = $toId;
                $friendItem->friend_id = $fromId;
                $friendItem->getApprovalTime();
                $friendItem->relationshipStatus = 'approved';
                $friendItem->create();
                $friendItem->self(array('*'));
            }

            //Already have relationship, update to approved
            if($friendItem->relationshipStatus != 'approved'){
                $friendItem->user_id = $toId;
                $friendItem->friend_id = $fromId;
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

        $fromId = $item->user_id;
        $toId = $item->friend_id;

        $item->remove();

        $item->user_id = $toId;
        $item->friend_id = $fromId;
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

    protected function isStatusChangeAllow($status)
    {
        $item = $this->getItem();
        $allowChanges = $this->allowStatusChangeRules;
        if(false === in_array(array($item->relationshipStatus, $status), $allowChanges)){
            return false;
        }
        return true;
    }

    protected function updateFriendStatus($status, $bothSide = true)
    {
        $item = $this->getItem();
        $fromId = $item->user_id;
        $toId = $item->friend_id;

        switch($status){
            case 'approved' :
            $item->getApprovalTime();
            break;
            case 'refused' : 
            $item->getRefusedTime();
            break;
            case 'removed' :
            $item->getRemovedTime();
            break;
            case 'blocked' :
            $item->getBlockedTime();
            break;
            default:
            break;

        }
        $item->relationshipStatus = $status;
        $item->save();

        if(true === $bothSide){
            $friendItem = clone $item;
            $friendItem->user_id = $toId;
            $friendItem->friend_id = $fromId;
            $friendItem->save();
            unset($friendItem);
        }
    }

}
