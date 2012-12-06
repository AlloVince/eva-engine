<?php
require_once './autoloader.php';

$form = new \Zend\Form\Form();
$form->add(array(
    'name' => 'username',
    'type'  => 'Zend\Form\Element\Text',
));

$subForm = new \Zend\Form\Form();
$subForm->setName('subform');
$subForm->add(array(
    'name' => 'email',
    'type'  => 'Zend\Form\Element\Text',
));

$form->add($subForm);

$form->prepare();

$helper = new Zend\Form\View\Helper\FormText();
echo $helper($form->get('username'));
echo $helper($form->get('subform')->get('email'));

