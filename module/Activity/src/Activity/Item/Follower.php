<?php

namespace Activity\Item;

use Eva\Mvc\Item\AbstractItem;

class Follower extends AbstractItem
{
    protected $dataSourceClass = 'Activity\DbTable\Followers';

    protected $map = array(
        'create' => array(
            'getFollowerId()',
            'getFollowTime()',
        ),
    );

    public function getFollowerId()
    {
        if(!$this->follower_id){
            $user = \Core\Auth::getLoginUser();
            $this->follower_id = $user['id'];
        }
    }

    public function getFollowTime()
    {
        if(!$this->followTime) {
            return $this->followTime = \Eva\Date\Date::getNow();
        }
    }


}
