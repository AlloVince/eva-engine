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
class Form extends \Zend\Form\Form implements InputFilterProviderInterface
{

    /**
    * Element definitions
    *
    * @var array
    */
    protected $baseElements = array();

    /**
    * Tobe Merge Element definitions
    *
    * @var array
    */
    protected $mergeElements = array();

    /**
    * Merged Element definitions
    *
    * @var array
    */
    protected $mergedElements = array();

    /**
    * Filter definitions
    *
    * @var array
    */
    protected $baseFilters = array();

    /**
    * Tobe Merge Filter definitions
    *
    * @var array
    */
    protected $mergeFilters = array();

    /**
    * Merged Filter definitions
    *
    * @var array
    */
    protected $mergedFilters = array();

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

    //protected $fieldsMap = array();

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
    protected $elementsInited = false;

    /**
    * Form filters inited
    *
    * @var boolean
    */
    protected $filtersInited = false;

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
    * Sub Form Groups
    *
    * @var array
    */
    protected $subFormGroups = array(
        'default' => array()
    );

    /**
    * Parent Form
    *
    * @var Eva\Form\RestfulForm
    */
    protected $parent;


    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function add($elementOrFieldset, array $flags = array())
    {
        parent::add($elementOrFieldset, $flags);
        if(is_array($elementOrFieldset) && isset($elementOrFieldset['name'])){
            $elementOrFieldset = $this->get($elementOrFieldset['name']);
        }
        if ($elementOrFieldset instanceof RestfulForm) {
            $this->setParent($this);
        }
        return $this;
    }

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
        $this->initFileTransfer();
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
        if($this->mergedElements){
            return $this->mergedElements;
        }
        //TODO: could merge parent class elements
        $elements = $elements ? $elements : $this->mergeElements;
        return $this->mergedElements = $this->merge($this->baseElements, $elements);
    }

    public function getElementsArray()
    {
        return $this->mergedElements;
    }

    public function searchElementsArray($elementName)
    {
        if(isset($this->mergedElements[$elementName])){
            return $this->mergedElements[$elementName];
        }
        return array();
    }

    public function mergeFilters()
    {
        if($this->mergedFilters){
            return $this->mergedFilters;
        }
        return $this->mergedFilters = $this->merge($this->baseFilters, $this->mergeFilters);
    }

    public function getFiltersArray()
    {
        return $this->mergedFilters;
    }

    public function searchFiltersArray($filterName)
    {
        if(isset($this->mergedFilters[$filterName])){
            return $this->mergedFilters[$filterName];
        }
        return array();
    }

    public function useSubFormGroup($groupName = 'default')
    {
        if(!isset($this->subFormGroups[$groupName])){
            throw new Exception\InvalidArgumentException(sprintf(
                'Sub Form Group %s not defined in Form %s', $groupName, get_class($this)
            ));
        }
        $subForms = $this->subFormGroups[$groupName];
        $this->setSubForms($subForms);
        return $this;
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

    public function addSubForm($formName, $formConfig = array())
    {
        if($formConfig instanceof Form){

            $subForm = $formConfig;
            $subForm->setName($formName);

        } else {

            if(is_array($formConfig)) {

                if(isset($formConfig[0])){
                    $subFormClass = $formConfig[0];
                } elseif(isset($formConfig['formClass'])) {
                    $subFormClass = $formConfig['formClass'];
                } else {
                    throw new Exception\InvalidArgumentException(sprintf(
                        'Subform %s not find defined class', $formName
                    ));
                }

            } elseif(is_string($formConfig)){

                $subFormClass = $formConfig;

            } else {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Subform %s config not correct, require string or object', $formName
                ));
            }

            if(!class_exists($subFormClass)){
                return $this;
            }
            $subForm = new $subFormClass($formName);
        }

        $subForm->setParent($this);
        if(is_array($formConfig) && isset($formConfig['collection']) && $formConfig['collection']) {
            if(isset($formConfig['optionsCallback'])){
                $optionsCallback = $formConfig['optionsCallback'];
                $optionsCallback = $subForm->$optionsCallback();
                $formConfig = array_merge($formConfig, $optionsCallback);
            }

            $object = isset($formConfig['object']) ? $formConfig['object'] : array();
            if(is_object($object) && method_exists($object, 'toArray')) {
                $values = $object->toArray();
            } else {
                $values = (array) $object;
            }

            $options = array(
                'allow_add' => true,
                'target_element' => $subForm,
            );
            $options = array_merge($options, $formConfig);

            if($values) {
                $options['count'] = count($values);
            }

            $this->add(array(
                'type' => 'Zend\Form\Element\Collection',
                'name' => $formName,
                'options' => $options,
            ));
            $this->get($formName)->populateValues($object);
        } else {
            $this->add($subForm);
        }
        return $this; 
    }

    public function init(array $options = array())
    {
        $elements = $this->mergeElements();
        foreach($elements as $element){
            $this->initElement($element);
        }
        return $this;
    }

    public function afterInit()
    {
        return $this;
    }

    protected function initElement(array $element)
    {
        //Element not correct
        if(!isset($element['name'])) {
            return;
        }

        $element = $this->autoElementId($element);
        if(isset($element['type']) && false === strpos($element['type'], '\\')){
            $element['type'] = 'Zend\Form\Element\\' . ucfirst($element['type']);
        }
        if(isset($element['callback']) && $element['callback']){
            $callback = (string) $element['callback'];
            if(method_exists($this, $callback)){
                $element = $this->$callback($element);
            }
        }
        return $this->add($element);
    }

    protected function initFilters()
    {
        if(true === $this->filtersInited){
            return $this;
        }

        $filters = $this->mergeFilters();
        //Note: some Validators need inject full user input data
        foreach($filters as $key => $filter){
            if(isset($filter['validators']) && is_array($filter['validators'])) {
                foreach($filter['validators'] as $validKey => $validator){
                    if(isset($validator['injectdata']) && $validator['injectdata']){
                        $filters[$key]['validators'][$validKey]['options']['data'] = $this->data;
                    }
                }
            }
        }

        $formFactory  = $this->getFormFactory();
        $inputFactory = $formFactory->getInputFilterFactory();

        if (null === $this->filter) {
            $inputFilter = $this->filter = new InputFilter();
        } else {
            $inputFilter = $this->filter;
        }

        foreach($filters as $name => $filter){
            $input = $inputFactory->createInput($filter);
            $inputFilter->add($input, $name);
        }
        $this->filtersInited = true;
        return $this;
    }

    public function initFileTransfer()
    {
        $elements = $this->getElementsArray();
        $fileElements = array();
        foreach($elements as $key => $element){
            if(isset($element['type']) && $element['type'] == 'file'){
                $fileElements[$key] = $element;
            }
        }

        if(!$fileElements){
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

        $mergeFilters = $this->getFiltersArray();
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

    public function getInputFilterSpecification()
    {
        return $this->mergeFilters();
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

    public function setAction($action)
    {
        $this->setAttribute('action', $action);
        return $this;
    }

    public function restful($inputName = '_method')
    {
        return sprintf('<input type="hidden" name="' . $inputName . '" value="%s">', $this->restfulMethod);
    }

    public function isValid()
    {
        $this->initFilters();
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


    public function getData($flag = FormInterface::VALUES_NORMALIZED)
    {
        $data = parent::getData($flag);
        $data = $this->prepareData($data);
        return $data;
    }

    public function prepareData($data)
    {
        return $data;
    }

    public function beforeBind($valuesOrObject)
    {
        return $valuesOrObject;
    }

    public function bind($valuesOrObject, $flags = FormInterface::VALUES_NORMALIZED)
    {
        $valuesOrObject = $this->beforeBind($valuesOrObject);

        if(!$valuesOrObject){
            return $this;
        }

        if(is_array($valuesOrObject) || $valuesOrObject instanceof \Zend\Stdlib\Parameters){
            $this->setData((array) $valuesOrObject);
        } else {
            parent::bind($valuesOrObject);
        }

        $this->afterBind();
        return $this;
    }

    public function afterBind()
    {
        return $this;
    }

    public function isError($elementNameOrArray)
    {
        $element = $this->getElement($elementNameOrArray);
        return $element->getMessages() ? true : false;
    }


    protected function autoElementId(array $element, $idPrefix = null)
    {
        if(!$this->autoElementId){
            return $element;
        }

        //TODO:form collection fix
        $idPrefix = $idPrefix ? $idPrefix : $this->getIdPrefix();
        if(!isset($element['name'])){
            return $element;
        }
        $elementId = isset($element['attributes']['id']) ? $element['attributes']['id'] : $element['name'];
        $elementId = $idPrefix . '-' . $elementId;
        $elementId = str_replace(array('\\', '_', '[', ']'), '-', strtolower($elementId));
        $elementId = trim($elementId, '-');
        $element['attributes']['id'] = $elementId;
        return $element;
    }

    public function getElement($elementNameOrArray)
    {
        if(true === is_string($elementNameOrArray)){
            return $this->get($elementNameOrArray);
        }

        if(true === is_array($elementNameOrArray) && isset($elementNameOrArray[0]) && isset($elementNameOrArray[1])){
            $fieldsetName = $elementNameOrArray[0];
            $elementName = $elementNameOrArray[1];
        } elseif(is_string($elementNameOrArray)) {
            $fieldsetName = '';
            $elementName = $elementNameOrArray;
        } else {
            throw new Exception\InvalidArgumentException(sprintf('Element Name require string or array'));
        }

        if($fieldsetName) {
            $element = $this->get($fieldsetName)->get($elementName);
        } else {
            $element = $this->get($elementName);
        }

        if(!$element){
            throw new Exception\InvalidArgumentException(sprintf('Request Element %s not found', $elementName));
        }

        return $element;
    }

    public function getFilter($filterNameOrArray)
    {
        $this->mergeFilters();
        if(true === is_string($filterNameOrArray)){
            return $this->searchFiltersArray($filterNameOrArray);
        }

        if(true === is_array($filterNameOrArray) && isset($filterNameOrArray[0]) && isset($filterNameOrArray[1])){
            $fieldsetName = $filterNameOrArray[0];
            $filterName = $filterNameOrArray[1];
        } elseif(is_string($filterNameOrArray)) {
            $fieldsetName = '';
            $filterName = $filterNameOrArray;
        } else {
            throw new Exception\InvalidArgumentException(sprintf('Filter Name require string or array'));
        }

        if($fieldsetName) {
            $this->get($fieldsetName)->mergeFilters();
            $filter = $this->get($fieldsetName)->searchFiltersArray($filterName);
        } else {
            $filter = $this->searchFiltersArray($filterNameOrArray);
        }

        if(!$filter){
            throw new Exception\InvalidArgumentException(sprintf('Request Filter %s not found', $filterName));
        }

        return $filter;
    }


    public function helper($elementNameOrArray, $attrsOrInputType = null, array $attrs = array(), array $options = array())
    {
        $view = $this->getView();
        if(!$view && $this->parent){
            $view = $this->parent->getView();
        }
        if(!$view){
            throw new Exception\InvalidArgumentException(sprintf('Form view not found'));
        }

        $element = $this->getElement($elementNameOrArray);
        if(!$element){
            throw new Exception\InvalidArgumentException(sprintf('Request element %s not found', $elementNameOrArray));
        }

        $defaultOptions = array(
            'i18n' => true,
            'replace' => true,
            'reorder' => false,
            'args' => array(),
            'type' => 'formInput',
            'validator' => true,
        );
        $options = array_merge($defaultOptions, $options);

        if($attrsOrInputType){
            if(is_string($attrsOrInputType)){
                //TODO:: if type changed, should re-generate new element
                $options['type'] = $attrsOrInputType;
            } elseif(is_array($attrsOrInputType)){
                $attrs = $attrsOrInputType;
            } else {
                throw new Exception\InvalidArgumentException(sprintf('Element %s attributes require array.', $elementNameOrArray));
            }
        }

        if(isset($attrs['type']) && $attrs['type']){
            $options['type'] = $attrs['type'];
            unset($attrs['type']);
        }

        //Merge attributes
        if(false === $options['replace']){
            $attrs = $this->merge($element->getAttributes(), $attrs);
        } else {
            $attrs = array_merge($element->getAttributes(), $attrs);
        }
        $element->setAttributes($attrs);

        if(isset($attrs['label'])){
            $element->setLabel($attrs['label']);
        }

        if(isset($attrs['value'])){
            $element->setValue($attrs['value']);
        }

        //For form multi checkbox
        if(isset($attrs['checkedValue'])){
            $element->setCheckedValue($attrs['checkedValue']);
        }
        if(isset($attrs['checked'])){
            $element->setChecked($attrs['checked']);
        }

        $filter = $this->getFilter($elementNameOrArray);
        //Merge Value Options
        if(isset($attrs['value_options']) && method_exists($element, 'getValueOptions')){
            $element->setValueOptions($this->merge($element->getValueOptions(), $attrs['value_options']));
        }
        return $view->input($element, $options, $filter); 
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

    public function __construct($name = null, $subFormGroup = null)
    {
        parent::__construct($name);
        $this->init();
        $this->afterInit();

        if(Api::_()->getServiceManager()->has('translator')){
            $translator = \Zend\I18n\Translator\Translator::factory(array(
                'locale' => Api::_()->getServiceManager()->get('translator')->getLocale(),
                'translation_file_patterns' => array(
                    'zf' => array(
                        'type' => 'PhpArray',
                        'base_dir' => EVA_LIB_PATH . '/Zend/resources/languages/',
                        'pattern' => '%s/Zend_Validate.php'
                    ),
                ),
            ));
            \Zend\Validator\AbstractValidator::setDefaultTranslator($translator);
        }
    }
}
