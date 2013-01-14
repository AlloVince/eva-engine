<?php

namespace Blog\Item;

use Eva\Mvc\Item\AbstractItem;
use michelf\Markdown;
use michelf\MarkdownExtra;

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
        if($blogItem->codeType == 'html'){
            return $this->ContentHtml = $this->content;
        } elseif ($blogItem->codeType == 'reStructuredText'){
            $rst = new \RST_Parser();
            return $this->ContentHtml = $rst->transform($this->content);
        } else {
            return $this->ContentHtml = MarkdownExtra::defaultTransform($this->content);
        }
    }

    public function getPreview()
    {
        if(!$this->Preview) {
            return $this->Preview = strip_tags($this->content);
        }
    }
}
