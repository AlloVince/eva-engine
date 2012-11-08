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


class NewForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'username' => array (
            'name' => 'username',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Username',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $baseFilters = array (
        'username' => array (
            'name' => 'username',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}

$form = new \NewForm();
$form->setData(array(
    'username' => '',
));
$form->isValid();
p($form->getMessages());
