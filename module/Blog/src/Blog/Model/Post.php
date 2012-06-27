<?php

namespace Blog\Model;

use Eva\Mvc\Model\AbstractModel;

class Post extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Posts';

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
        'savePost'
    );

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
