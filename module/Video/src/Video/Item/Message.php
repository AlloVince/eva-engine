<?php

namespace Video\Item;

use Eva\Mvc\Item\AbstractItem;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Video\Service\TextParser;

class Message extends AbstractItem
{
    protected $dataSourceClass = 'Video\DbTable\Messages';

    protected $relationships = array(
        'File' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'File',
            'joinColumns' => array(
                'joinColumn' => 'message_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Video\Item\MessageFile',
            'inversedMappedBy' => 'MessageFile',
            'inverseJoinColumns' => array(
                'joinColumn' => 'file_id',
                'referencedColumn' => 'id',
            ),
        ),
        'MessageFile' => array(
            'targetEntity' => 'Video\Item\MessageFile',
            'relationship' => 'OneToMany',
            'joinColumn' => 'message_id',
            'referencedColumn' => 'id',
            'joinParameters' => array(
            ),
        ),
    );

    protected $map = array(
        'create' => array(
            'getMessageHash()',
            'getCreateTime()',
            'getUserId()',
        ),
        'save' => array(
            'getUrlName()',
            'getUpdateTime()',
        ),
    );

    private $parser;

    public function getParser()
    {
        return $this->parser = TextParser::factory($this->content, array(), $this->getServiceLocator());
    }

    public function getMessageHash()
    {
        if(!$this->messageHash){
            return $this->messageHash = \Eva\Stdlib\String\Hash::uniqueHash();
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

    public function getContentHtml()
    {
        //$text = $this->content;
        $parser = $this->getParser();
        $this->ContentHtml = $parser->toHtml();
    }
}
