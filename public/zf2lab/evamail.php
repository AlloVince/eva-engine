<?php
require_once './autoloader.php';

use Eva\Mail\Message;
use Zend\Mail\Transport;
use Zend\Mail\Exception;
use Zend\Di\Di;
use Zend\Di\Config as DiConfig;
$diConfig = array(
    'definition' => array('class' => array(
        'Zend\View\Resolver\AggregateResolver' => array(
            'attach' => array(
                'resolver' => array(
                    'required' => true,
                    'type'     => 'Zend\View\Resolver\TemplatePathStack',
                ),
            ),
        ),
        'Zend\Mail\Message' => array(
            'setTo' => array(
                'emailOrAddressList' => array(
                    'type' => false,
                    'required' => true
                ),
                'name' => array(
                    'type' => false,
                    'required' => false
                ),
            ),
            'addTo' => array(
                'emailOrAddressList' => array(
                    'type' => false,
                    'required' => true
                ),
                'name' => array(
                    'type' => false,
                    'required' => false
                ),
            ),
            'setFrom' => array(
                'emailOrAddressList' => array(
                    'type' => false,
                    'required' => true
                ),
                'name' => array(
                    'type' => false,
                    'required' => false
                ),
            ),
            'addFrom' => array(
                'emailOrAddressList' => array(
                    'type' => false,
                    'required' => true
                ),
                'name' => array(
                    'type' => false,
                    'required' => false
                ),
            ),
            'setSender' => array(
                'emailOrAddressList' => array(
                    'type' => false,
                    'required' => true
                ),
                'name' => array(
                    'type' => false,
                    'required' => false
                ),
            ),
        ),
    )),
    'instance' => array(
        'Zend\View\Resolver\TemplatePathStack' => array(
            'parameters' => array(
                'paths'  => array(
                    Message::VIEW_PATH_NAME => EVA_ROOT_PATH . '/data/',
                ),
            ),
        ),
        'Zend\View\Resolver\AggregateResolver' => array(
            'injections' => array(
                'Zend\View\Resolver\TemplatePathStack',
            ),
        ),
        'Zend\View\Renderer\PhpRenderer' => array(
            'parameters' => array(
                'resolver' => 'Zend\View\Resolver\AggregateResolver',
            ),
        ),
        'Zend\Mail\Transport\FileOptions' => array(
            'parameters' => array(
                'path' => EVA_ROOT_PATH . '/data/mail',
            )
        ),
        'Zend\Mail\Transport\File' => array(
            'injections' => array(
                'Zend\Mail\Transport\FileOptions'
            )
        ),
        'Zend\Mail\Transport\SmtpOptions' => array(
            'parameters' => array(
                'name'              => 'sendgrid',
                'host'              => 'smtp.sendgrid.net',
                'port' => 25,
                'connectionClass'  => 'login',
                'connectionConfig' => array(
                    'username' => 'allo.vince@gmail.com',
                    'password' => 'password',
                ),
            )
        ),
        'Eva\Mail\Message' => array(
            'parameters' => array(
                'headers' => 'Zend\Mail\Headers',
                'view' => 'Zend\View\Renderer\PhpRenderer',
                'viewModel' => 'Zend\View\Model\ViewModel',
                'Zend\Mail\Message::setTo:emailOrAddressList' => 'allo.vince@gmail.com',
                'Zend\Mail\Message::setTo:name' => 'EvaEngine',
                'Zend\Mail\Message::setFrom:emailOrAddressList' => 'info@evaengine.com',
                'Zend\Mail\Message::setFrom:name' => 'EvaEngine',
            )
        ),
        'Zend\Mail\Transport\Smtp' => array(
            'injections' => array(
                'Zend\Mail\Transport\SmtpOptions'
            )
        ),
    )
);

$di = new Di();
$di->configure(new DiConfig($diConfig));

$transport = $di->get('Zend\Mail\Transport\Smtp');
$transport = $di->get('Zend\Mail\Transport\File');
$message = $di->get('Eva\Mail\Message');
$message->setSubject("Mail Subject")
->setBody('Mail Content');
$transport->send($message);
