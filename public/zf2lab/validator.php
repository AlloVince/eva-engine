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
Zend\Validator\AbstractValidator::setDefaultTranslator($translator);


$validatorChain = new Zend\Validator\ValidatorChain();
$validatorChain
->addValidator(
    new Zend\Validator\StringLength(array('min' => 6,
    'max' => 12))
)
->addValidator(new Zend\Validator\NotEmpty());

$username = '';
// Validate the username
if ($validatorChain->isValid($username)) {
    // username passed validation
} else {
    // username failed validation; print reasons
    foreach ($validatorChain->getMessages() as $message) {
        p($message);
    }
}

