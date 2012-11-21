<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Core;

use Eva\Api;
use Eva\Config\Config;
use Eva\Mail\Message;
use Zend\Mail\Transport;
use Zend\Mail\Exception;
use Zend\Di\Di;
use Zend\Di\Config as DiConfig;
use Zend\Mail\Message as ZendMessage;

/**
 * Core Mail
 *
 * @category   Core
 * @package    Core_Mail
 */
class Mail
{
    protected $message;
    protected $transports;
    protected $conflictTransports = array('sendmail', 'smtp', 'queue');

    protected $transportsClasses = array(
        'sendmail' => 'Zend\Mail\Transport\Sendmail',
        'smtp' => 'Zend\Mail\Transport\Smtp',
        'file' => 'Zend\Mail\Transport\File',
    );

    public function setTransportsClasses(array $classes)
    {
        $this->transportsClasses = $classes;
        return $this;
    }

    public function setMessage(ZendMessage $message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getTransport($type)
    {
        if(isset($this->transports[$type])) {
            return $this->transports[$type];
        }
    }

    public function send(ZendMessage $message = null)
    {
        $message = $message ? $message : $this->message;
        if(!$message){
            throw new Exception\InvalidArgumentException(sprintf(
                'Mail message not set'
            ));
        }

        $transports = $this->transports;
        if(!$transports){
            throw new Exception\InvalidArgumentException(sprintf(
                'Mail transport not set'
            ));
        }

        $conflictTransports = $this->conflictTransports;
        $transportTypes = array_keys($transports);

        if(count(array_intersect($conflictTransports, $transportTypes)) > 1){
            throw new Exception\InvalidArgumentException(sprintf(
                'Mail transports conflicted by %s',
                implode(",", array_intersect($conflictTransports, $transportTypes))
            ));
        }

        $zendMessage = new ZendMessage();
        $zendMessage->setBody($message->getBody());
        $zendMessage->setHeaders($message->getHeaders());
        $zendMessage->setEncoding($message->getEncoding());
        $message = $zendMessage;
        foreach($transports as $transportType => $transport){
            $transport->send($message);
        }

    }

    public function __construct(array $config = array())
    {
        $defaultConfig = array(
            'transports' => array(
                'smtp'     => false,
                'sendmail' => false,
                'file'     => true,
            ),
            'message' => array(
            ),
            'di' => array(
                'definition' => array(
                    'class' => array(
                        'Zend\View\Resolver\AggregateResolver' => array(
                            'attach' => array(
                                'resolver' => array(
                                    'required' => true,
                                    'type'     => 'Zend\View\Resolver\TemplatePathStack',
                                ),
                            ),
                        ),
                    ),
                ),
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
                    //Zend View Di Config End
                    'Eva\Mail\Message' => array(
                        'parameters' => array(
                            'headers' => 'Zend\Mail\Headers',
                            'view' => 'Zend\View\Renderer\PhpRenderer',
                            'viewModel' => 'Zend\View\Model\ViewModel',
                            'encoding' => 'UTF-8',
                        )
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
                    /*
                    'Zend\Mail\Transport\SmtpOptions' => array(
                        'parameters' => array(
                            'name'              => 'sendgrid',
                            'host'              => 'smtp.sendgrid.net',
                            'port' => 25,
                            'connectionClass'  => 'login',
                            'connectionConfig' => array(
                                'username' => 'username',
                                'password' => 'password',
                            ),
                        )
                    ),
                    */
                    'Zend\Mail\Transport\Smtp' => array(
                        'injections' => array(
                            'Zend\Mail\Transport\SmtpOptions'
                        )
                    ),
                )
            ),
        );

        $globalConfig = Api::_()->getConfig();
        if(isset($globalConfig['mail'])){
            $config = Config::mergeArray($defaultConfig, $globalConfig['mail'], $config);
        } else {
            $config = Config::mergeArray($defaultConfig['mail'], $config);
        } 

        $diConfig = array();
        if($config['di']){
            $diConfig = $config['di'];
        }
        $di = new Di();
        $di->configure(new DiConfig($diConfig));
        $this->message = $di->get('Eva\Mail\Message');

        $allowTransports = $this->transportsClasses;
        $transportType = '';
        if(is_string($config['transports'])){
            $transportType = $config['transports'];
            $transportClass = isset($allowTransports[$transportType]) ? $allowTransports[$transportType] : null;
            if(!$transportClass){
                throw new Exception\InvalidArgumentException(sprintf(
                    'Unknow transport type %s in method %s"',
                    $transportType,
                    __METHOD__
                ));
            }

            $transport = $di->get($transportClass);
            //\Zend\Di\Display\Console::export($di);
            $this->transports[$transportType] = $transport;
        } elseif(is_array($config['transports'])){
            $transportTypes = $config['transports'];
            foreach($transportTypes as $transportType => $value) {
                if (!$value) {
                    continue;
                }
                $transportClass = isset($allowTransports[$transportType]) ? $allowTransports[$transportType] : null;
                if(!$transportClass){
                    throw new Exception\InvalidArgumentException(sprintf(
                        'Unknow transport type %s in method %s"',
                        $transportType,
                        __METHOD__
                    ));
                }
                $this->transports[$transportType] = $di->get($transportClass);   
            }
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects a string or array as transport config, "%s" received',
                __METHOD__,
                gettype($config['transports'])
            ));
        }
    }

}
