<?php
require_once './autoloader.php';

$translator = Zend\I18n\Translator\Translator::factory(array(
    'locale' => 'zh',
    'translation_file_patterns' => array(
        'zf' => array(
            'type' => 'PhpArray',
            'base_dir' => EVA_LIB_PATH . '/Zend/resources/languages/',
            'pattern' => '%s/Zend_Validate.php'
        ),
    ),
));
\Zend\Validator\AbstractValidator::setDefaultTranslator($translator);

$form = new \Zend\Form\Form();
$name = array(
        'name' => 'username',
        'options' => array(
            'label' => 'Your name',
        ),
        'attributes' => array(
            'type'  => 'text'
        ),
);
$form->add($name);


$filter = $form->getInputFilter();
$filter->remove('username');
$filter->add(array(
    'name' => 'username',
    'required' => true,
    'validators' => array (
        'stringLength' => array (
            'name' => 'StringLength',
            'options' => array (
                'max' => '3',
            ),
        ),
    ),
));
$form->setInputFilter($filter);

//$form->prepare();
$form->setData(array(
    'username' => 'sadjksafjas:',
));
$form->prepare();
//p($form->getInputFilter());
$form->isValid();
p($form->getMessages());
