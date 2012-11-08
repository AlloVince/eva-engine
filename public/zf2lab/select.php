<?php
require_once './autoloader.php';

$form = new \Zend\Form\Form();
$select = array(
    'name' => 'cat_id',
    'type' => 'Zend\Form\Element\Select',
    'options' => array(
        'label' => 'Categoria',
        'value_options' => array(
            '' => '',
        ),
    ),
);
$form->add($select);
$form->get('cat_id')->setValueOptions(array('foo' => 'bar'));
$helper = new Zend\Form\View\Helper\FormSelect();
echo $helper($form->get('cat_id'));



$formV = new \Zend\Form\Form();
$formV->add(array(
    'name' => 'cat_id',
    'type' => 'Zend\Form\Element\Select',
    'options' => array(
        'label' => 'Categoria',
        'value_options' => array(
            '' => '',
        ),
    ),
    'require' => true,
    'filters'  => array(
        array('name' => 'Int'),
    ),
));
$formV->setData(array(
    'cat_id' => 'bar',
));
$formV->prepare();
echo $formV->isValid();
