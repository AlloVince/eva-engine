<?php

namespace Blog\Model\Post;

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

    public function getUrl($urlName)
    {
        return '/blog/post/' . $urlName;
    }

    public function getText()
    {
        $textTable = Api::_()->getDbTable('Blog\DbTable\Texts');
        $item = $this->item;
        $text = $textTable->where(array('post_id' => $item['id']))->find("one");
        if(!$text){
            return array();
        }
        return $text;
    }

    public function getContentHtml($contentHtml)
    {
        if($contentHtml){
            return $contentHtml;
        }

        $item = $this->item;
        $text = $item['Text'];
        $content = isset($text['content']) ? $text['content'] : '';
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
