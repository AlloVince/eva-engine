<?php
    
namespace Webservice\Adapter;

use Webservice\Exception;
use Zend\Http\Response;
use Zend\Http\Client;

abstract class AbstractUniform
{
    protected $adapter;

    protected $cache;

    protected $defaultApi;

    protected $mapping;

    protected $mapKey;

    protected function api($mapItem)
    {
        if(is_string($mapItem)){
            $api = $this->defaultApi;
            $apiDataKey = $mapItem;
        } else {
            if(!isset($mapItem['fromApi'])){
                throw new Exception\InvalidArgumentException(sprintf(
                    'Data source api not found in %s', get_class($this)
                ));
            }
            $api = $mapItem['fromApi'];
            $apiDataKey = $mapItem['key'];
        }

        $cache = $this->cache;
        if(!isset($cache[$api])){
            $data = $this->adapter->api($api);
            $cache[$api] = $data;
        }

        if(isset($cache[$api][$apiDataKey])){
            return $cache[$api][$apiDataKey];
        }
    }

    public function __call($methodName, $arguments) 
    {
        $mapping = $this->mapping;
        if(0 === strpos($methodName, 'from')){
            $methodKey = substr($methodName, 4);
            if(!isset($mapping[$methodKey])){
                throw new Exception\InvalidArgumentException(sprintf(
                    'Request method %s not defined in %s', $methodName, get_class($this)
                ));
            }
            $this->mapKey = $methodKey;
            return $this;
        } elseif(0 === strpos($methodName, 'get')){
            $mapKey = $this->mapKey;
            $methodKey = lcfirst(substr($methodName, 3));
            if(!isset($mapping[$mapKey][$methodKey])){
                throw new Exception\InvalidArgumentException(sprintf(
                    'Request method %s not defined in %s', $methodName, get_class($this)
                ));
            }
            $mapItem = $mapping[$mapKey][$methodKey];
            return $this->api($mapItem);
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Request method %s not defined in %s', $methodName, get_class($this)
            ));
        }
        return $data;

    }

    public function getData()
    {
        $mapping = $this->mapping;
        $data = array();

        foreach($mapping as $dataType => $subMapping){
            foreach($subMapping as $key => $mapItem){
                $data[$dataType][$key] = $this->api($mapItem);
            }
        }
        return $data;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }
}
