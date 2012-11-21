<?php
require_once './autoloader.php';

$appGlobelConfig = include EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php';
$appLocalConfig = EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.local.config.php';
if(file_exists($appLocalConfig)){
    $appLocalConfig = include $appLocalConfig;
    $appGlobelConfig = array_merge($appGlobelConfig, $appLocalConfig);
}
Zend\Mvc\Application::init($appGlobelConfig);

use Core\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport;
use Zend\Di\Di;
use Zend\Di\Config as DiConfig;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part;

$mail = new Mail();

$message = $mail->getMessage();
$message->setSubject('Eva Mail Subject')
->setData(array(
    'user' => 'AlloVince'
))
->setTemplatePath(__DIR__)
->setTemplate('mail/template')
->addAttachment('loading.jpg');
$mail->send($message);
