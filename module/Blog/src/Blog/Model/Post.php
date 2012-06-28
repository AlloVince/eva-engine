<?php

namespace Blog\Model;

use Eva\Mvc\Model\AbstractModel;

class Post extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Posts';

    protected $data;

    protected $user;

    protected $events = array(
        'createPost.pre',
        'createPost',
        'createPost.post',
        'savePost.pre',
        'savePost',
        'savePost.post',
        'removePost.pre',
        'removePost',
        'removePost.post',
        'getPost.pre',
        'getPost',
        'getPost.post',
        'getPostList.pre',
        'getPostList',
        'getPostList.post',
    );

    protected $auto = array(
        'createPostByAdmin'
    );

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setData(array $data = array())
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function createPost()
    {
        $this->getEvent()->trigger('createPost.pre', $this);

        $data = $this->getData();
        $data = $this->getItemArray($data, array(
            'urlName' => array('urlName', 'getUrlName'),
            'createTime' => array('createTime', 'getCreateTime'),
            'updateTime' => array('updateTime', 'getUpdateTime'),
        ));
        $itemTable = $this->getItemTable();
        $itemTable->create($data);

        return $itemTable->getLastInsertValue();
    }
}
