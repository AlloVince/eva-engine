<?php

namespace Blog\Model\Category;

use Eva\Mvc\Model\AbstractItem,
    Eva\Api;

class Item extends AbstractItem
{
    protected $date;

    public function getCreateTime()
    {
        return \Eva\Date\Date::getNow();
    }

    public function getUpdateTime()
    {
        return \Eva\Date\Date::getNow();
    }

    public function getUserId()
    {
    }

    public function getUserName()
    {
    }

    public function getUrlName($urlName)
    {
        if($urlName){
            return $urlName;
        }
        return \Eva\Stdlib\String\Hash::uniqueHash();
    }

    public function getContentHtml($contentHtml)
    {
        if($contentHtml){
            return $contentHtml;
        }

        $item = $this->item;
        if($item['codeType'] != 'html' && $content){
            switch($item['codeType']){
                case 'markdown':
                return $this->toMarkdown($content);
                default:
                return $content;
            }
        }
        return $content;
    }

    protected function toMarkdown($content)
    {
        require_once EVA_LIB_PATH . '/Markdown/markdownextra.php';
        $markdown = new \MarkdownExtra_Parser();
        return $markdown->transform($content);
    }
}
