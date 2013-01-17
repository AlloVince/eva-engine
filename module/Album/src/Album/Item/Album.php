<?php

namespace Album\Item;

use Eva\Mvc\Item\AbstractItem;

class Album extends AbstractItem
{
    protected $dataSourceClass = 'Album\DbTable\Albums';
    
    protected $inverseRelationships = array(
        'Cover' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'OneToOne',
            'joinColumn' => 'id',
            'referencedColumn' => 'cover_id',
            'joinParameters' => array(
            ),
        ),
    );

    protected $relationships = array(
        'Count' => array(
            'targetEntity' => 'Album\Item\Count',
            'relationship' => 'OneToOne',
            'joinColumn' => 'album_id',
            'referencedColumn' => 'id',
        ),
        'File' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'File',
            'joinColumns' => array(
                'joinColumn' => 'album_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Album\Item\AlbumFile',
            'inversedMappedBy' => 'AlbumFile',
            'inverseJoinColumns' => array(
                'joinColumn' => 'file_id',
                'referencedColumn' => 'id',
            ),
        ),
        'AlbumFile' => array(
            'targetEntity' => 'Album\Item\AlbumFile',
            'relationship' => 'OneToMany',
            'joinColumn' => 'album_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
        'User' => array(
            'targetEntity' => 'User\Item\User',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'User',
            'joinColumns' => array(
                'joinColumn' => 'album_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Album\Item\AlbumUser',
            'inversedMappedBy' => 'AlbumUser',
            'inverseJoinColumns' => array(
                'joinColumn' => 'user_id',
                'referencedColumn' => 'id',
            ),
        ),
        'Category' => array(
            'targetEntity' => 'Album\Item\Category',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'Category',
            'joinColumns' => array(
                'joinColumn' => 'album_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Album\Item\CategoryAlbum',
            'inversedMappedBy' => 'CategoryAlbum',
            'inverseJoinColumns' => array(
                'joinColumn' => 'category_id',
                'referencedColumn' => 'id',
            ),
        ),
        'CategoryAlbum' => array(
            'targetEntity' => 'Album\Item\CategoryAlbum',
            'relationship' => 'OneToMany',
            'joinColumn' => 'album_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
    );

    protected $map = array(
        'create' => array(
            'getUrlName()',
            'getCreateTime()',
            'getUserId()',
            'getUserName()',
        ),
        'save' => array(
            'getUrlName()',
        ),
    );
    
    public function getUrlName()
    {
        if(!$this->urlName) {
            return $this->urlName = \Eva\Stdlib\String\Hash::uniqueHash();
        }
    }

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
            $this->user_id = $user['id'];
        }
    }

    public function getUserName()
    {
        if(!$this->user_name){
            $user = \Core\Auth::getLoginUser();
            $this->user_name = $user['userName'];
        }
    }
}
