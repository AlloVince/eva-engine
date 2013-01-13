<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Form
 * @author    AlloVince
 */

namespace Eva\Form;

use Eva\Api;
use Zend\Form\Fieldset;
use Zend\Form\FormInterface;
use Zend\Form\FieldsetInterface;
use Zend\Config\Config;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\Factory as FilterFactory;
use Eva\File\Transfer\TransferFactory;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class UploadForm extends Form implements UploadFormInterface
{
    /**
    * File Transfer
    *
    * @var Eva\File\Transfer\TransferFactory
    */
    protected $fileTransfer;

    /**
    * File Transfer Options
    *
    * @var array
    */
    protected $fileTransferOptions = array();

    protected $fileTransferMessages;

    public function setFileTransferOptions($fileTransferOptions)
    {
        $this->fileTransferOptions = $fileTransferOptions;
        return $this;
    }

    public function getFileTransferOptions()
    {
        return $this->fileTransferOptions;
    }

    public function setFileTransfer(TransferFactory $fileTransfer)
    {
        $this->fileTransfer = $fileTransfer;
        return $this;
    }

    public function getFileTransfer()
    {
        if($this->fileTransfer){
            return $this->fileTransfer;
        }
        $this->initFileTransfer();
        $fileTransferOptions = $this->getFileTransferOptions();
        return $fileTransferOptions ? $this->fileTransfer = TransferFactory::factory($fileTransferOptions) : $this->fileTransfer;
    }

    public function getFileTransferMessages()
    {
        return $this->fileTransferMessages;
    }

    public function initFileTransfer()
    {

        $elements = $this->mergeElements();
        $fileElements = array();
        foreach($elements as $key => $element){
            if(isset($element['type']) && $element['type'] == 'file'){
                $fileElements[$key] = $element;
            }
        }

        //If form implements UploadFormInterface, fileTransfer will be created by force
        if(!$fileElements && !($this instanceof \Eva\Form\UploadFormInterface)){
            return $this;
        }

        $config = array(
            'di' => array('instance' => array(
                'Eva\File\Transfer\Adapter\Http' => array(
                    'parameters' => array(
                        'validators' => array(
                        ),
                        'filters' => array(
                        ),
                    ),
                ),
                'Eva\File\Transfer\Transfer' => array(
                    'parameters' => array(
                        'adapter' => 'Eva\File\Transfer\Adapter\Http',
                    ),
                ),
            )
        ));

        $mergeFilters = $this->mergeFilters();
        foreach($fileElements as $key => $element){
            if(isset($mergeFilters[$key]['validators'])){
                foreach($mergeFilters[$key]['validators'] as $validator){
                    $config['di']['instance']['Eva\File\Transfer\Adapter\Http']['parameters']['validators'][] = array(
                        $validator['name'], true, $validator['options'], $element['name']
                    ); 
                }
            }
            if(isset($mergeFilters[$key]['filters'])){
                foreach($mergeFilters[$key]['filters'] as $filter){
                    $config['di']['instance']['Eva\File\Transfer\Adapter\Http']['parameters']['filters'][$filter['name']] = $filter['options'];
                }
            }
            if(isset($mergeFilters[$key]['options'])){
                $config['di']['instance']['Eva\File\Transfer\Adapter\Http']['parameters']['options'] = $mergeFilters[$key]['options'];
            }
        }

        $this->fileTransferOptions = $config;
        return $this;
    }

    public function isValid()
    {
        if(!$this->getFileTransfer()){
            return parent::isValid();
        }
        $elementValid = parent::isValid();
        $fileValid = $this->fileTransfer->isValid();
        $result = $elementValid && $fileValid;
        $this->isValid = $result;
        if (!$result) {
            $fileTransferMessages = $this->fileTransfer->getMessages();
            if($fileTransferMessages){
                $this->fileTransferMessages = $fileTransferMessages = $this->resortFileTransferMessages($fileTransferMessages);
            }
            $this->setMessages($fileTransferMessages);
        }
        return $result;
    }

    protected function resortFileTransferMessages(array $messages)
    {
        if(!$messages){
            return $messages;
        }
        $resortedMessages = array();
        foreach($messages as $key => $message){
            $key = preg_replace('/_\d+_$/', '', $key);
            $resortedMessages[$key][] = $message;
        }
        return $resortedMessages;
    }
}
