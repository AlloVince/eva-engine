<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;

class Text extends AbstractItem
{
    protected $dataSourceClass = 'Blog\DbTable\Texts';

    protected $map = array(
        'create' => array(
        ),
    );

    public function getContentHtml()
    {
        $blogItem = $this->getModel()->getItem();
        if($blogItem->codeType != 'html'){
            require_once EVA_LIB_PATH . '/Markdown/markdownextra.php';
            $markdown = new \MarkdownExtra_Parser();
            return $this->ContentHtml = $markdown->transform($this->content);
        }
    }
}
