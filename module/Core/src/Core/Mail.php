<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Session
 */

namespace Core;

use Eva\Api;
use Eva\Mail\Message;
use Zend\Mail\Transport;
use Zend\Mail\Exception;
use Zend\Di\Di;
use Zend\Di\Configuration as DiConfiguration;

/**
 * Session ManagerInterface implementation utilizing ext/session
 *
 * @category   Zend
 * @package    Zend_Session
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

    public function setMessage(Message $message)
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

    public function send(Message $message = null)
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
        foreach($transports as $transportType => $transport){
            $transport->send($message);
        }

    }

    public function __construct(array $config = array())
    {
        $defaultConfig = array(
            'transports' => array('file'),
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
                    //Zend View Di Config Start
                    /*
                    'Zend\View\Resolver\TemplateMapResolver' => array(
                        'parameters' => array(
                            'map'  => array(
                                Message::VIEW_PATH_NAME => EVA_ROOT_PATH . '/data/mail/abc.phtml',
                            ),
                        ),
                    ),
                    */
                    'Zend\View\Resolver\TemplatePathStack' => array(
                        'parameters' => array(
                            'paths'  => array(
                                Message::VIEW_PATH_NAME => EVA_ROOT_PATH . '/data/',
                            ),
                        ),
                    ),
                    'Zend\View\Resolver\AggregateResolver' => array(
                        'injections' => array(
                            //'Zend\View\Resolver\TemplateMapResolver',
                            'Zend\View\Resolver\TemplatePathStack',
                        ),
                    ),
                    'Zend\View\Renderer\PhpRenderer' => array(
                        'parameters' => array(
                            'resolver' => 'Zend\View\Resolver\AggregateResolver',
                        ),
                    ),
                    'Zend\View\Mode\ViewModel' => array(
                        'parameters' => array(
                        ),
                    
                    ),
                    //Zend View Di Config End

                    'Zend\Mail\Headers' => array(
                        'parameters' => array(
                            //  'Zend\Mail\Message::addTo:emailOrAddressList' => 'me@mwop.net',
                            //  'Zend\Mail\Message::setSender:emailOrAddressList' => 'me@mwop.net',
                        )
                    ),
                    'Eva\Mail\Message' => array(
                        'parameters' => array(
                            'headers' => 'Zend\Mail\Headers',
                            'view' => 'Zend\View\Renderer\PhpRenderer',
                            'viewModel' => 'Zend\View\Model\ViewModel',
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

                    'Zend\Mail\Transport\SmtpOptions' => array(
                        'parameters' => array(
                            'name'              => 'sendgrid',
                            'host'              => 'smtp.sendgrid.net',
                            'port' => 25,
                            'connection_class'  => 'login',
                            'connection_config' => array(
                                'username' => 'username',
                                'password' => 'password',
                            ),
                        )
                    ),
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
            $config = array_merge($defaultConfig, $globalConfig['mail'], $config);
        } else {
            $config = array_merge($defaultConfig['mail'], $config);
        } 

        $diConfig = array();
        if($config['di']){
            $diConfig = $config['di'];
        }
        $di = new Di();
        $di->configure(new DiConfiguration($diConfig));
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
            //$transports = array();
            $transportTypes = $config['transports'];
            foreach($transportTypes as $transportType) {
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
