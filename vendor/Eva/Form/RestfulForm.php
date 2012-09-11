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

use Zend\Form\Form;
use Zend\Config\Config;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as FilterFactory;
use Eva\File\Transfer\TransferFactory;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class RestfulForm extends Form
{

    /**
    * Element definitions
    *
    * @var array
    */
    protected $baseElements = array();

    /**
    * Merge Element definitions
    *
    * @var array
    */
    protected $mergeElements = array();

    /**
    * Filter definitions
    *
    * @var array
    */
    protected $baseFilters = array();

    /**
    * Merge Filter definitions
    *
    * @var array
    */
    protected $mergeFilters = array();

    /**
    * Resful form method
    *
    * @var array
    */
    protected $restfulMethod;

    /**
    * If true, will generate elements id
    *
    * @var boolean
    */
    protected $autoElementId = true;

    /**
    * Element id prefix
    *
    * @var string
    */
    protected $idPrefix;

    protected $fieldsMap = array();


    /**
    * Sub Forms
    *
    * @var array
    */
    protected $subForms = array();

    /**
    * Form element inited
    *
    * @var boolean
    */
    protected $elementInited = false;

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


    /**
    * Mvc View
    *
    * @var Zend\View
    */
    protected $view;

    public function getView()
    {
        return $this->view;
    }

    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

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
        return $this->fileTransfer = TransferFactory::factory($this->getFileTransferOptions());
    }

    public function setAutoElementId($autoElementId)
    {
        $this->autoElementId = (boolean) $autoElementId;
        return $this;
    }

    public function getAutoElementId()
    {
        return $this->autoElementId;
    }

    public function setIdPrefix($idPrefix)
    {
        $this->idPrefix = (string) $idPrefix;
        return $this;
    }

    public function getIdPrefix()
    {
        if($this->idPrefix){
            return $this->idPrefix;
        }

        return $this->idPrefix = get_class($this);
    }


    public function setMergeElements(array $elements = array())
    {
        $this->mergeElements = $elements;
        return $this;
    }

    public function getMergeElements()
    {
        return $this->mergeElements;
    }

    public function mergeElements(array $elements = array())
    {
        //TODO: could merge parent class elements
        $elements = $elements ? $elements : $this->mergeElements;
        return $this->merge($this->baseElements, $elements);
    }

    public function mergeFilters()
    {
        return $this->merge($this->baseFilters, $this->mergeFilters);
    }

    public function setSubForms(array $subForms = array())
    {
        foreach($subForms as $formName => $formConfig){
            $this->addSubForm($formName, $formConfig);
        }
        return $this;
    }

    public function getSubForm($subFormName)
    {
        return $this->get($subFormName);
    }

    protected function addSubForm($formName, array $formConfig = array()) 
    {
        if(isset($formConfig[0])){
            $subFormClass = $formConfig[0];
        } elseif(isset($formConfig['formClass'])) {
            $subFormClass = $formConfig['formClass'];
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Subform %s not find defined class', $formName
            ));
        }

        if(!class_exists($subFormClass)){
            return $this;
        }


        if(isset($formConfig['collection']) && $formConfig['collection']) {
            $subForm = new $subFormClass();
            $object = $formConfig['object'];
            $subForm->setView($this->view);
            $this->add(array(
                'type' => 'Zend\Form\Element\Collection',
                'name' => $formName,
                'options' => array(
//                    'count' => count($values),
                    'count' => 2,
                    'should_create_template' => false,
                    'allow_add' => true,
                    'target_element' => $subForm,
                )
            ));
            $this->get($formName)->setObject($object);//->extract();
        } else {
            /*
            $subForm = new $subFormClass();
            $subElements = $subForm->getMergedElements();
            $subFilters = $subForm->getMergedFilters();

            $fieldset = new \Zend\Form\Fieldset($formName);
            $factory = $this->getFormFactory();
            foreach($subElements as $subElementKey => $subElement){
                $subElement['attributes']['data-subform-name'] = $formName;
                $subElement = $this->initElement($subElement);
                $subElement = $this->autoElementId($subElement, $subFormClass);
                $fieldset->add($factory->create($subElement));
            }
            $this->add($fieldset);
            */
        }



        return $this; 
    }

    public function init(array $options = array())
    {
        $elements = $this->mergeElements();
        foreach($elements as $element){
            $this->initElement($element);
        }
        //p($this->elements);
    }

    protected function initElement(array $element)
    {
        if(isset($element['type']) && false === strpos($element['type'], '\\')){
            $element['type'] = 'Zend\Form\Element\\' . ucfirst($element['type']);
        }
        return $this->add($element);
    }

    protected function initFilter()
    {
    }

    public function setMethod($method = '')
    {
        if(!$method){
            return $this;
        }

        $allowMethods = array('get', 'put', 'post', 'delete');
        $method = strtolower($method);

        if(false === in_array($method, $allowMethods)){
            throw new Exception\UnexpectedMethodException(sprintf(
                'Input Method %s is not correct http method',
                __METHOD__,
                $method
            ));
        }

        $restfulMethod = 'get';
        switch($method){
            case 'post' :
            $restfulMethod = 'post';
            break;
            case 'get' :
            break;
            case 'put' :
                $restfulMethod = 'put';
                $method = 'post';
                break;
            case 'delete' :
                $restfulMethod = 'delete';
                $method = 'post';
                break;
            default:
        }

        $this->setAttribute('method', $method);
        $this->restfulMethod = $restfulMethod;
        return $this;
    }

    public function restful($inputName = '_method')
    {
        return sprintf('<input type="hidden" name="' . $inputName . '" value="%s">', $this->restfulMethod);
    }

    public function isValid()
    {
        if(!$this->fileTransfer){
            return parent::isValid();
        }

        $elementValid = parent::isValid();
        $fileValid = $this->fileTransfer->isValid();
        $result = $elementValid && $fileValid;
        $this->isValid = $result;
        if (!$result) {
            $this->setMessages($this->fileTransfer->getMessages());
        }
        return $result;
    }

    public function isInvalid()
    {
    }

    public function isError($elementName)
    {
        $element = $this->get($elementName);
        return $element->getMessages() ? true : false;
    }



    /*
    public function getElement($elementNameOrArray)
    {
        if(true === is_string($elementNameOrArray)){
            return $this->get($elementNameOrArray);
        }

        if(true === is_array($elementNameOrArray) && isset($elementNameOrArray[0]) && isset($elementNameOrArray[1])){
            $fieldset = $this->get($elementNameOrArray[0]);
            return $fieldset->get($elementNameOrArray[1]);
        }

        throw new Exception\UnexpectedElementException(sprintf(
            '%s Request element %s not correct',
            __METHOD__,
            $elementNameOrArray
        ));
    }
    */

    protected function autoElementId(array $element, $idPrefix = null)
    {
        if(!$this->autoElementId){
            return $element;
        }

        $idPrefix = $idPrefix ? $idPrefix : $this->getIdPrefix();
        $elementId = isset($element['attributes']['id']) ? $element['attributes']['id'] : $element['name'];
        $elementId = $idPrefix . '-' . $elementId;
        $elementId = str_replace(array('\\', '_', '[', ']'), '-', strtolower($elementId));
        $elementId = trim($elementId, '-');
        $element['attributes']['id'] = $elementId;
        return $element;
    }

    public function helper($elementName, $optionOrInputType = null, array $options = array(), array $setting = array())
    {
        $defaultSetting = array(
            'i18n' => true,
            'replace' => true,
            'reorder' => false,
        );
        $setting = array_merge($defaultSetting, $setting);

        $view = $this->getView();
        if(!$view){
            throw new Exception\InvalidArgumentException(sprintf('Form view not found'));
        }

        $element = $this->get($elementName);
        if(!$element){
            throw new Exception\InvalidArgumentException(sprintf('Request Element %s not found', $elementName));
        }

        if($optionOrInputType){
            if(is_string($optionOrInputType)){
                $options = array_merge(array('type' => $optionOrInputType), $options);
            } else {
                $options = array_merge($optionOrInputType, $options);
            }
        }

        //Merge attributes
        if(false === $setting['replace']){
            $options = $this->merge($element->getAttributes(), $options);
        }

        //Merge Value Options
        if(isset($options['value_options']) && method_exists($element, 'getValueOptions')){
            $element->setValueOptions($this->merge($element->getValueOptions(), $options['value_options']));
        }
        return $view->input($element, $options); 
    }

    protected function merge(array $global, array $local)
    {
        if(!$local) {
            return $global;
        }

        $global = new Config($global);
        $local = new Config($local);
        $global->merge($local);
        return $global->toArray();
    }

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->init();
    }
}
