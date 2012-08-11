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

        if($postId && $this->getSubItem('CategoryPost')){
            $subData = $this->getSubItem('CategoryPost');
            $subTable = Api::_()->getDbTable('Blog\DbTable\CategoriesPosts');
            $subItem = $this->getItemClass($subData, array(
                'post_id' => array('post_id', 'getPostId')
            ), 'Blog\Model\CategoryPost\Item');
            $subData = $subItem->toArray();
            $subTable->where(array('post_id' => $postId))->remove();
            if($subData['category_id']) {
                $subTable->where(array('post_id' => $postId))->create($subData);
            }
        }
        
        if($postId && $this->getSubItem('FileConnect')){
            $subData = $this->getSubItem('FileConnect');
            $subTable = Api::_()->getDbTable('File\DbTable\FilesConnections');
            $subItem = $this->getItemClass($subData, array(
                'connect_id' => array('connect_id', 'getConnectId'),
                'connectType' => array('connectType', 'getConnectType')
            ), 'File\Model\FileConnect\Item');
            $subData = $subItem->toArray();
            $subTable->where(array('connect_id' => $postId, 'connectType' => $subData['connectType']))->remove();
            if($subData['connect_id'] && $subData['file_id']) {
                $subTable->where(array('connect_id' => $postId, 'connectType' => $subData['connectType']))->create($subData);
            }
        } 
        
        $this->getEvent()->trigger('createPost.post', $this);

        return $postId;
    }

    public function savePost()
    {
        $this->getEvent()->trigger('savePost.pre', $this);

        $item = $this->setItemAttrMap(array(
            'urlName' => array('urlName', 'getUrlName'),
            'updateTime' => array('updateTime', 'getUpdateTime'),
        ))->getItemArray();

        $postId = $item['id'];

        if(!$postId){
            throw new \Core\Model\Exception\InvalidArgumentException(sprintf(
                '%s post id not found',
                __METHOD__
            ));
        }
        $itemTable = $this->getItemTable();
        $itemTable->where(array('id' => $postId))->save($item);

        if($postId && $this->getSubItem('Text')){
            $textData = $this->getSubItem('Text');
            $textTable = Api::_()->getDbTable('Blog\DbTable\Texts');
            $textItem = $this->getItemClass($textData, array(
                'post_id' => array('post_id', 'getPostId')
            ), 'Blog\Model\Text\Item');
            $textData = $textItem->toArray();
            $textTable->where(array('post_id' => $postId))->save($textData);
        }

        if($postId && $this->getSubItem('CategoryPost')){
            $subData = $this->getSubItem('CategoryPost');

            $subTable = Api::_()->getDbTable('Blog\DbTable\CategoriesPosts');
            $subItem = $this->getItemClass($subData, array(
                'post_id' => array('post_id', 'getPostId')
            ), 'Blog\Model\CategoryPost\Item');
            $subData = $subItem->toArray();
            $subTable->where(array('post_id' => $postId))->remove();
            if($subData['category_id']) {
                $subTable->where(array('post_id' => $postId))->create($subData);
            }
        }

        if($postId && $this->getSubItem('FileConnect')){
            $subData = $this->getSubItem('FileConnect');
            $subTable = Api::_()->getDbTable('File\DbTable\FilesConnections');
            $subItem = $this->getItemClass($subData, array(
                'connect_id' => array('connect_id', 'getConnectId'),
                'connectType' => array('connectType', 'getConnectType')
            ), 'File\Model\FileConnect\Item');
            $subData = $subItem->toArray();
            $subTable->where(array('connect_id' => $postId, 'connectType' => $subData['connectType']))->remove();
            if($subData['connect_id'] && $subData['file_id']) {
                $subTable->where(array('connect_id' => $postId, 'connectType' => $subData['connectType']))->create($subData);
            }
        } 

        $this->getEvent()->trigger('savePost.post', $this);

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
                'CategoryPost' => array(
                    'category_id' => null,
                ),
                'FileConnect' => array(
                    'connect_id' => null,
                ),
                'File' => array(
                    'connect_id' => null,
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

        $defaultParams = array(
            'enableCount' => true,
            'keyword' => '',
            'status' => '',
            'visibility' => '',
            'page' => 1,
            'order' => 'iddesc',
        );
        $params = $this->getItemListParams();
        $params = new \Zend\Stdlib\Parameters(array_merge($defaultParams, $params));

        $itemTable = $this->getItemTable();

        $itemTable->selectPosts($params);
        $posts = $itemTable->find('all');
        //p($itemTable->debug());

        $this->getEvent()->trigger('getPost.postcache', $this);
        return $this->itemList = $posts;
    }

}
