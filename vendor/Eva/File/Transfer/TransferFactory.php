<?php

namespace Eva\File\Transfer;

use Eva\Api;
use Zend\Di\Di;
use Zend\Di\Config as DiConfig;

class TransferFactory
{
    public static function factory(array $config = array())
    {
        $defaultConfig = array(
            'di' => array(
                'definition' => array(
                ),
                'instance' => array(
                    'Eva\File\Transfer\Adapter\Http' => array(
                        'parameters' => array(
                            /*
                            'options' => array(
                                'ignoreNoFile' => false,
                                'useByteString' => true,
                                'detectInfos' => true
                            ),
                            'validators' => array(
                                array(
                                    'Extension', true, array(
                                        'extension' => array('txt'),
                                    ), 'upload'
                                ),
                            ),
                            'filters' => array(
                            ),
                            */
                        ),
                    ),
                    'Eva\File\Transfer\Transfer' => array(
                        'parameters' => array(
                            'adapter' => 'Eva\File\Transfer\Adapter\Http',
                        ),
                    ),
                )
            ),
        );

        $globalConfig = Api::_()->getConfig();
        if(isset($globalConfig['file_transfer'])){
            $config = array_merge($defaultConfig, $globalConfig['file_transfer'], $config);
        } else {
            $config = array_merge($defaultConfig, $config);
        } 

        $diConfig = array();
        if($config['di']){
            $diConfig = $config['di'];
        }
        $di = new Di();
        $di->configure(new DiConfig($diConfig));
        $fileTransfer = $di->get('Eva\File\Transfer\Transfer');
        
        //p($fileTransfer, 1);
        //@\Zend\Di\Display\Console::export($di);
        return $fileTransfer;
    }
}
