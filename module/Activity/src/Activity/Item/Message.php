<?php

namespace Activity\Item;

use Eva\Mvc\Item\AbstractItem;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Activity\Service\TextParser;

class Message extends AbstractItem
{
    protected $dataSourceClass = 'Activity\DbTable\Messages';

    protected $relationships = array(
        'File' => array(
            'targetEntity' => 'File\Item\File',
            'relationship' => 'ManyToMany',
            'mappedBy' => 'File',
            'joinColumns' => array(
                'joinColumn' => 'message_id',
                'referencedColumn' => 'id',
            ),
            'inversedBy' => 'Activity\Item\MessageFile',
            'inversedMappedBy' => 'MessageFile',
            'inverseJoinColumns' => array(
                'joinColumn' => 'file_id',
                'referencedColumn' => 'id',
            ),
        ),
        'MessageFile' => array(
            'targetEntity' => 'Activity\Item\MessageFile',
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
        $text = $this->content;
        $parser = TextParser::factory($text, array(), $this->getServiceLocator());
        $this->ContentHtml = $parser->toHtml();
    }
}
