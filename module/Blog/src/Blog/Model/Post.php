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
        'getPost.precache',
        'getPost.pre',
        'getPost',
        'getPost.post',
        'getPost.postcache',
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

        $this->getEvent()->trigger('createPost.post', $this);

        return $itemTable->getLastInsertValue();
    }

    public function getPost()
    {
        $this->getEvent()->trigger('getPost.precache', $this);

        $params = $this->getItemParams();
        if(!$params || !(is_numeric($params) || is_string($params))){
            throw new \Core\Model\Exception\InvalidArgumentException(sprintf(
                '%s params %s not correct',
                __METHOD__,
                $params
            ));
        }


        $this->getEvent()->trigger('getPost.pre', $this);

        $itemTable = $this->getItemTable();

        if(is_numeric($params)){
            $this->item = $post = $itemTable->where(array('id' => $params))->find('one');
        } else {
            $this->item = $post = $itemTable->where(array('urlName' => $params))->find('one');
        }

        $this->getEvent()->trigger('getPost', $this);

        if($post) {
            $this->item = $post = $this->getItemArray($post, array(
                'Url' => array('urlName', 'getUrl', 'callback'),
                'Text' => array(
                    'contentHtml' => array('contentHtml', 'getContentHtml'),
                ),
            ));
        }
        
        $this->getEvent()->trigger('getPost.post', $this);


        $this->getEvent()->trigger('getPost.postcache', $this);

        return $this->item = $post;
    }

}
