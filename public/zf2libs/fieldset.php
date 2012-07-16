<?php
require_once './autoloader.php';

$value = array(
    'title' => 'mytitle',
);
$value = new \ArrayObject($value);
$value['tag'] = new \ArrayObject(array(
    'tagname' => 'mytag'
));

$tagElement =  array(
    'name' => 'tagname',
    'attributes' => array(
        'type' => 'text',
        'label' => 'Post Tag Name',
    ),
);

$postElement =   array(
    'name' => 'title',
    'attributes' => array(
        'type' => 'text',
        'label' => 'Post Title',
    ),
);

$fieldset = new \Zend\Form\Fieldset('tag');
$fieldset->add($tagElement);

$form = new \Zend\Form\Form();
$form->add($postElement);
$form->add($fieldset);
$form->bind($value);
