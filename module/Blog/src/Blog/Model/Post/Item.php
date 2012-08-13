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
    
    public function getPreview($preview)
    {
        if($preview){
            return $preview;
        }
        
        $model = $this->model;
        $text = $model->getSubItem('Text');
        $content = isset($text['content']) ? $text['content'] : '';
        
        $preview = \Eva\Stdlib\String\Substring::subCNStringWithWrap(strip_tags($content), 80);
        
        return $preview;
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

    public function getCategoryPost()
    {
        $subTable = Api::_()->getDbTable('Blog\DbTable\CategoriesPosts');
        $item = $this->item;
        $res = $subTable->where(array('post_id' => $item['id']))->find("one");
        if(!$res){
            return array();
        }
        return $res;
    }

    protected function toMarkdown($content)
    {
        require_once EVA_LIB_PATH . '/Markdown/markdownextra.php';
        $markdown = new \MarkdownExtra_Parser();
        return $markdown->transform($content);
    }

    public function getFileConnect()
    {
        $subTable = Api::_()->getDbTable('File\DbTable\FilesConnections');
        $item = $this->item;
        $res = $subTable->where(array('connect_id' => $item['id'], 'connectType' => 'post'))->find("one");
        if(!$res){
            return array();
        }
        return $res;
    }

    public function getFile()
    {
        $item = $this->item;

        if (!isset($item['FileConnect']['file_id']) || !$item['FileConnect']['file_id']) {
            return array();
        }
        $subModel = Api::_()->getModel('File\Model\File');
        $res = $subModel->setItemParams($item['FileConnect']['file_id'])->getFile();
        if(!$res){
            return array();
        }
        return $res;
    }
}
