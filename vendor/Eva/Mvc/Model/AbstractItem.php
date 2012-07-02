<?php
namespace Eva\Mvc\Model;

use Eva\Mvc\Exception;

class AbstractItem 
{
    protected $item;

    protected $model;

    protected $config;

    public function toArray($config = array())
    {
        $item = $this->item;

        $defaultConfig = $this->config;
        $config = array_merge($defaultConfig, $config);

        foreach($config as $attrName => $attrConfig){
            $item = $this->handlerConfig($attrName, $attrConfig, $item);
        }

        $this->item = $item;
        return (array) $item;
    }

    protected function handlerConfig($attrName, $attrConfig, $item)
    {
        $configType = gettype($attrConfig);
        if(!$attrConfig){
            return $item;
        }

        if(false === isset($item[$attrName])){
            $item[$attrName]  = null;
        }
        $handlerTypes = array('field', 'function', 'callback');

        switch($configType) {
            case 'array':
            if(true === isset($attrConfig[0])){
                $handlerType = 'callback';
                if(isset($attrConfig[2]) && in_array($attrConfig[2], $handlerTypes)){
                    $handlerType = $attrConfig[2];
                }

                $itemInputAttr = isset($item[$attrConfig[0]]) ? $item[$attrConfig[0]] : null;

                if($handlerType == 'field'){
                    $item[$attrName] = $attrConfig[1];
                } elseif($handlerType == 'function') {
                    $functionName = $attrConfig[1];
                    $functionArgs = isset($attrConfig[3]) && is_array($attrConfig[3]) ? $attrConfig[3] : array();
                    $item[$attrName] = call_user_func_array($functionName, $functionArgs);
                } elseif($handlerType == 'callback') {
                    $functionName = $attrConfig[1];
                    $class = isset($attrConfig[3]) && is_array($attrConfig[3]) ? $attrConfig[3] : $this;
                    $item[$attrName] = call_user_func_array(array(
                        &$class,
                        $functionName,
                    ), array($itemInputAttr));

                }

                /*
                p(array(
                    'handlerType' => $handlerType,
                    'attrName' => $attrName,
                    'class' => get_class($class),
                    'functionName' => $functionName,
                    'itemInputAttr' => $itemInputAttr,
                    'res' => call_user_func_array(array(
                        $class,
                        $functionName,
                    ), array($itemInputAttr)),
                ));
                */

            } else {

                $functionName = 'get' . $attrName;
                if(method_exists($this, $functionName)){
                    $item[$attrName] = $this->$functionName();
                }
                foreach($attrConfig as $subAttrName => $subAttrConfig){
                    $item[$attrName] = $this->handlerConfig($subAttrName, $subAttrConfig, $item[$attrName]);
                }
            }

            break;
            case 'boolean':
            case 'integer':
            case 'double':
            case 'string':
            $item[$attrName] = $attrConfig;
            break;
            default:
            throw new Exception\InvalidArgumentException(sprintf(
                'item attr %s not allow type %s',
                $attrName,
                $configType
            ));
        }

        return $item;
    }

    public function __construct($item, $model = null, $config = array())
    {
        if(true === is_array($item)){
            $this->item = $item = new \ArrayObject($item);
        }
        if(!$item instanceof \ArrayObject){
            throw new Exception\InvalidArgumentException(sprintf(
                '%s not allow item type %s',
                __METHOD__,
                gettype($item)
            ));
        }
        $this->item = $item;
        $this->model = $model;
        $this->config = $config;
    }
}
