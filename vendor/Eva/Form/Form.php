<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Eva\Form;

use Zend\Config\Config;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as FilterFactory;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class Form extends \Zend\Form\Form
{
    protected $baseElements = array();
    protected $mergeElements = array();
    protected $subElements = array();
    
    protected $baseFilters = array();
    protected $mergeFilters = array();
    protected $subFilters = array();

    protected $formMethod;
    protected $restfulMethod;

    protected $autoElementId = true;
    protected $idPrefix;

    protected $fieldsMap = array();

    protected $subForms = array();

    protected $elementInited = false;
    protected $subFormInited = array();

    protected $fileTransfer;
    protected $fileTransferOptions = array();

    public function setFileTransferOptions($fileTransferOptions)
    {
        $this->fileTransferOptions = $fileTransferOptions;
        return $this;
    }

    public function getFileTransferOptions()
    {
        return $this->fileTransferOptions;
    }

    public function setFileTransfer($fileTransfer)
    {
        $this->fileTransfer = $fileTransfer;
        return $this;
    }

    public function getFileTransfer()
    {
        if($this->fileTransfer){
            return $this->fileTransfer;
        }
        return $this->fileTransfer = \Eva\File\Transfer\TransferFactory::factory($this->getFileTransferOptions());
    }

    public function setAutoElementId($autoElementId)
    {
        $this->autoElementId = (boolean) $autoElementId;
        return $this;
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

    public function getMergedElements()
    {
        return $this->merge($this->baseElements, $this->mergeElements);
    }

    public function getMergedFilters()
    {
        return $this->merge($this->baseFilters, $this->mergeFilters);
    }

    public function setSubForms(array $subForms = array())
    {
        foreach($subForms as $formName => $subForm){
            $this->addSubForm($formName, $subForm);
        }
        return $this;
    }

    public function subForm($subFormName)
    {
        return $this->get($subFormName);
    }

    protected function addSubForm($formName, array $subFormConfig = array()) 
    {
        //Sub form will be allow init once
        if(isset($this->subFormInited[$formName]) && true === $this->subFormInited[$formName]){
            return $this;
        }

        $subFormClass = $subFormConfig[0];
        $subForm = new $subFormClass;
        $this->subElements[$formName] = $subElements = $subForm->getMergedElements();
        $this->subFilters[$formName] = $subFilters = $subForm->getMergedFilters();

        $fieldset = new \Zend\Form\Fieldset($formName);
        $factory = $this->getFormFactory();
        foreach($subElements as $subElementKey => $subElement){
            $subElement['attributes']['data-subform-name'] = $formName;
            $subElement = $this->initElement($subElement);
            $subElement = $this->autoElementId($subElement, $subFormClass);
            $fieldset->add($factory->create($subElement));
        }
        $this->add($fieldset);


        $this->baseFilters = array_merge($this->baseFilters, 
            array(
                $formName => array_merge( array('type' => 'Zend\InputFilter\InputFilter'), $subFilters)
            )
        );

        $this->subFormInited[$formName] = true;
        return $this; 
    }

    //TODO: $form->get('title') when title is null should throw a new Exception 
    public function fieldsMap($data = array(), $quickMode = false, $skipFieldStart = '_')
    {
        if(is_object($data)){
            $data = $data->toArray();
        }

        if(!$data || !$this->fieldsMap && $quickMode === false){
            return $data;
        }

        if(true === $quickMode){
            foreach($data as $key => $value){
                if(false === strpos($key, $skipFieldStart)){
                    continue;
                }

                unset($data[$key]);
            }
        } else {

            $fieldsMap = $this->fieldsMap;
            $newData = array();
            foreach($data as $key => $value){
                if(isset($fieldsMap[$key])){
                    $newData[$fieldsMap[$key]] = $value;
                }
            }
            $data = $newData;
            unset($newData);
        }


        return $data;
    }

    public function init(array $options = array())
    {
        $defaultOptions = array(
            'action' => '',
            'values' => array(),
            'method' => 'get',    
        );

        $options = array_merge($defaultOptions, $options);

        $method = $options['method'];
        if($method){
            $this->setMethod($method);
        }

        $action = $options['action'];
        if($action){
            $this->setAttribute('action', $action);
        }

        if(false === $this->elementInited){
            $elements = $this->getMergedElements();

            foreach($elements as $name => $element){
                $element = $this->autoElementId($element);
                $element = $this->initElement($element);
                $this->add($element);
            }
            $this->elementInited = true;
        }


        $values = $options['values'];
        if($values){
            if($values instanceof \ArrayObject){
                $this->bind($values);
            } elseif(is_array($values)) {
                $this->bind(new \ArrayObject((array) $values));
            } elseif(is_object($values) && method_exists($values, 'toArray')) {
                $this->bind(new \ArrayObject($values->toArray()));
            } else {
                $this->bind($values);
            }
        }

        return $this;
    }

    protected function initElement(array $element)
    {
        $element = $this->uniformMultiInputInterface($element);
        //Element must have a certain type in new zf2 changes
        if(!isset($element['type']) && isset($element['attributes']['type'])){
            //TODO: maybe here will have custom
            $element['type'] = 'Zend\Form\Element\\' . ucfirst($element['attributes']['type']);
        }

        //TODO: just for following zf2 changes, will be removed
        if(!isset($element['options']['value_options']) && isset($element['attributes']['options'])){
            $element['options']['value_options'] = $element['attributes']['options'];
            unset($element['attributes']['options']);
        }

        return $element;
    }

    public function enableFileTransfer()
    {
        $elements = $this->getMergedElements();
        $fileElements = array();
        foreach($elements as $key => $element){
            if(isset($element['attributes']['type']) && $element['attributes']['type'] == 'file'){
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

        $mergeFilters = $this->getMergedFilters();
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
        $this->getFileTransfer();
        return $this;
    }

    public function enableFilters(array $filterOptions = array())
    {
        $filters = $this->merge($this->getMergedFilters(), $filterOptions);

        if(!$filters){
            $inputFilter = new InputFilter;
            $this->setInputFilter($inputFilter);
            return $this;
        }

        //TODO: use di here
        $requireDbAdapter = array(
            'dbnorecordexists',
            'dbrecordexists',
            'eva\validator\db\norecordexistsexcludeself',
        );
        foreach($filters as $key => $filter){
            //Auto close received option here
            //if(!isset($filters[$key]['required'])){
               // $filters[$key]['required'] = false;
            //}

            if(!isset($filter['validators']) || !is_array($filter['validators'])){
                continue;
            }



            foreach($filter['validators'] as $validateKey => $validator){
                if(isset($validator['name']) && in_array(strtolower($validator['name']), $requireDbAdapter)){
                    $filters[$key]['validators'][$validateKey]['options']['adapter'] = \Eva\Api::_()->getDbAdapter();
                }
                if(isset($validator['options']['exclude']['field']) && $validator['options']['exclude']['field'] && !isset($validator['options']['exclude']['value'])){
                    $filters[$key]['validators'][$validateKey]['options']['exclude']['value'] = $this->data[$validator['options']['exclude']['field']];
                }
            }
        }

        $factory = new FilterFactory();
        $inputFilter = $factory->createInputFilter($filters);
        $this->setInputFilter($inputFilter);
        return $this;
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

    public function restful()
    {
        //TODO: restful input name should be able to custom
        return sprintf('<input type="hidden" name="_method" value="%s">', $this->restfulMethod);
    }

    public function populateValues($data)
    {
        if (!is_array($data) && !$data instanceof Traversable) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Traversable set of data; received "%s"',
                __METHOD__,
                (is_object($data) ? get_class($data) : gettype($data))
            ));
        }

        foreach ($data as $name => $value) {
            if (!$this->has($name)) {
                continue;
            }

            $element = $this->get($name);
            //Fixed if bind value is ArrayObject;
            if ($element instanceof \Zend\Form\FieldsetInterface && (is_array($value) || $value instanceof \ArrayObject)) {
                $element->populateValues((array) $value);
                continue;
            }

            $element->setAttribute('value', $value);
        }
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

    public function autoElementId(array $element, $idPrefix = null)
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

    public function uniformMultiInputInterface(array $element)
    {
        if(!isset($element['attributes']['type'])){
            return $element;
        }

        if($element['attributes']['type'] == 'radio' && isset($element['attributes']['options'][0]['value'])){
            $options = array();
            foreach($element['attributes']['options'] as $key => $option){
                $options[$option['label']] = $option['value'];
            }
            $element['attributes']['options'] = $options;
        }

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

        $element = $this->getElement($elementName);
        $view = \Eva\Api::_()->getView();
        if($optionOrInputType){
            if(is_string($optionOrInputType)){
                $options = array_merge(array('type' => $optionOrInputType), $options);
            } else {
                $options = array_merge($optionOrInputType, $options);
            }
        }

        //Merge options with element attributes
        if(false === $setting['replace']){
            $options = $this->merge($element->getAttributes(), $options);
        }

        if(method_exists($element, 'getValueOptions') && isset($options['value_options'])){
            $element->setValueOptions($this->merge($element->getValueOptions(), $options['value_options']));
        }

        return $view->input($element, $options); 
    }

    public function isError($elementName)
    {
        $element = $this->getElement($elementName);
        return $element->getMessages() ? true : false;
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
}
