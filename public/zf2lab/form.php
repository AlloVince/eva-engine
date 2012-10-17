<?php
require_once './autoloader.php';

$form = new \Zend\Form\Form();
$form->add(array(
    'name' => 'send',
    'type'  => 'Zend\Form\Element\Image',
    'attributes' => array(
        'value' => 'Send',
        'src' => 'abc.jpg',
    ),
));

$helper = new Zend\Form\View\Helper\FormImage();
echo $helper($form->get('send'));
