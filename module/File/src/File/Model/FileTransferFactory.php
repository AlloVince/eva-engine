<?php

namespace File\Model;

use Eva\Api;
use Zend\Di\Di;
use Zend\Di\Config as DiConfig;

class FileTransferFactory
{
    public static function factory(array $config = array())
    {
        $defaultConfig = array(
            'di' => array(
                'definition' => array(
                ),
                'instance' => array(
                    'Zend\File\Transfer\Adapter\Http' => array(
                        'parameters' => array(
                            'options' => array('ignoreNoFile' => false, 'useByteString' => true, 'detectInfos' => true),
                            'validators' => array(
                                array('MimeType', true, array('image/jpeg')), // no files
                                array('FilesSize', true, array('max' => '1MB')), // no files
                                array('Count', true, array('min' => 1, 'max' => '1'), 'bar'), // 'bar' from config
                                array('MimeType', true, array('image/jpeg'), 'bar'), // 'bar' from config
                            ),
                            'filters' => array(
                                'Word\SeparatorToCamelCase' => array('separator' => ' '),
                            ),
                        ),
                    ),
                    'File\Model\FileTransfer' => array(
                        'parameters' => array(
                            'adapter' => 'Zend\File\Transfer\Adapter\Http',
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
        $fileTransfer = $di->get('File\Model\FileTransfer');
        
        p($fileTransfer, 1);
        //\Zend\Di\Display\Console::export($di);
        return $fileTransfer;
    }
}
