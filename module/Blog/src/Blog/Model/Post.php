<?php

namespace Blog\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Post extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Posts';

    protected $data;
    protected $subData;

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
        'getPostList.precache',
        'getPostList.pre',
        'getPostList',
        'getPostList.post',
        'getPostList.postcache',
    );

    /*
    public function setData(array $data = array(), array $subItemsMap = array())
    {
        if($subItemsMap){
            $subData = array();
            foreach($data as $key => $value){
                if(!isset($subItemsMap[$key])){
                    continue;
                }
                $subData[$key] = $value;
                unset($data[$key]);
            }
            $this->subData = $subData;
        }
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getSubData($dataKey)
    {
        if(isset($this->subData[$dataKey])){
            return $this->subData[$dataKey];
        }

        return $this->subData;
    }
    */

    public function createPost()
    {
        $this->getEvent()->trigger('createPost.pre', $this);

        $item = $this->setItemAttrMap(array(
            'urlName' => array('urlName', 'getUrlName'),
            'createTime' => array('createTime', 'getCreateTime'),
            'updateTime' => array('updateTime', 'getUpdateTime'),
        ))->getItemArray();

        $itemTable = $this->getItemTable();
        $itemTable->create($item);
        $postId = $itemTable->getLastInsertValue();


        if($postId){
            $item['id'] = $postId;
            $this->item = $item;
        }

        if($postId && $this->getSubItem('Text')){
            $textData = $this->getSubItem('Text');
            $textTable = Api::_()->getDbTable('Blog\DbTable\Texts');
            $textItem = $this->getItemClass($textData, array(
                'post_id' => array('post_id', 'getPostId')
            ), 'Blog\Model\Text\Item');
            $textData = $textItem->toArray();
            $textTable->create($textData);
        }
        

        $this->getEvent()->trigger('createPost.post', $this);

        return $postId;
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
            $this->item = $post = $this->setItemAttrMap(array(
                'Url' => array('urlName', 'getUrl', 'callback'),
                'Text' => array(
                    'contentHtml' => array('contentHtml', 'getContentHtml'),
                ),
            ))->getItemArray();
        }
        
        $this->getEvent()->trigger('getPost.post', $this);


        $this->getEvent()->trigger('getPost.postcache', $this);

        return $this->item = $post;
    }

    public function getPosts()
    {
    
        $this->getEvent()->trigger('getPostList.precache', $this);


        $params = $this->getItemListParams();
        $itemTable = $this->getItemTable();
        $posts = $itemTable->enableCount()->order('id DESC')->page($page)->find('all');

        $this->getEvent()->trigger('getPost.postcache', $this);
        return $this->itemList = $posts;
    }

}
