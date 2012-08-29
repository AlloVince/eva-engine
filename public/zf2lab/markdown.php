<?php
require_once './autoloader.php';
require_once EVA_LIB_PATH . '/Markdown/markdownextra.php';

$safeParser = true;
if($safeParser){
    $md = new Markdown_Parser();
    $md->no_markup = true;
    $md->no_entities = true;
} else {
    $md = new MarkdownExtra_Parser();
    $md->no_markup = true;
    $md->no_entities = true;
}

$text = file_get_contents('test.md');
$text = $md->transform($text);
echo $text;
