<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class Comment extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\Comments';

    protected $relationships = array(
    );

    protected $map = array(
        'create' => array(
            'getCreateTime()',
            'getUserId()',
            'getUserName()',
            'getIp()',
        ),
        'save' => array(
        ),
    );

    public function getCreateTime()
    {
        if(!$this->createTime) {
            return $this->createTime = \Eva\Date\Date::getNow();
        }
    }

    public function getUserId()
    {
        if(!$this->user_id){
            $user = \Core\Auth::getLoginUser();
            if($user){
                return $this->user_id = $user['id'];
            }
        }
    }

    public function getUserName()
    {
        if(!$this->user_name){
            $user = \Core\Auth::getLoginUser();
            if($user){
                return $this->user_name = $user['userName'];
            }
        }
    }

    public function getIp()
    {
        if(!$this->ip){
            return $this->ip = $_SERVER["REMOTE_ADDR"];
        }
    }

    public function getContentHtml()
    {
        if($this->codeType == 'html'){
            return $this->ContentHtml = $this->content;
        } else{
            $md = new \Markdown_Parser();
            $md->no_markup = true;
            $md->no_entities = true;
            return $this->ContentHtml = $md->transform($this->content);
        }
    }

}
