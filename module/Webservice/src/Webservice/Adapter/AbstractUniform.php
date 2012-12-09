<?php
    
namespace Webservice\Adapter;

use Webservice\Exception;
use Zend\Http\Response;
use Zend\Http\Client;

abstract class AbstractUniform
{
    protected $adapter;

    protected $apiMapping;

    protected $dataMapping;
    
    protected $mapKey;

    protected $lastRawResponse;

    protected function prepareApiParamsFromMapping($dataMapKey)
    {
        $mapping = $this->dataMapping;
        if(!isset($mapping[$dataMapKey])){
            throw new Exception\InvalidArgumentException(sprintf(
                'Request write api %s not defined in api mapping', $dataMapKey
            ));
        }
        $dataMapping = $mapping[$dataMapKey];
        if($dataMapping['Type'] !== 'Write'){
            throw new Exception\InvalidArgumentException(sprintf(
                'Request api %s is not able to write by mapping Type', $dataMapKey
            ));
        }

        $dataNodes = $dataMapping['Nodes'];
        $data = array();

        foreach($dataNodes as $key => $remoteDataKey){
            if(isset($params[$key])){
                $data[$remoteDataKey] = $params[$key];
            }
        }

        $apiParams = $this->prepareParams($dataMapKey, $dataMapping);

        return $apiParams;
    }


    protected function prepareParams($dataNode, $dataMap)
    {
        $defaultParams =  array(
            'api' => '',
            'method' => 'GET',
            'key' => '',
            'apiParams' => null,
            'requestParams' => array(),
            'security' => false,
            'beforeCallback' => '',
            'afterCallback' => '',
            'useDefault' => true,
        );
        
        if(is_string($dataNode)){
            $params = $this->apiMapping[$dataMap['Config']];
            $params = array_merge($defaultParams, $params);
            $params['key'] = $dataNode;
        } elseif (is_array($dataNode)){
            $params = array_merge($defaultParams, $dataNode);
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Api mapping require string or array'
            ));
        }

        return $params;
    }

    protected function api($dataNode, $dataMap)
    {
        $params = $this->prepareParams($dataNode, $dataMap);

        if(!$params['api']){
            throw new Exception\InvalidArgumentException(sprintf(
                'Data source api not found in %s', get_class($this)
            ));
        }

        if($params['beforeCallback']){
            if(false === method_exists($this, $params['beforeCallback'])){
                throw new Exception\InvalidArgumentException(sprintf(
                    'Callback %s not found in %s', $params['beforeCallback'], get_class($this)
                ));
            }
            $beforeCallback = $params['beforeCallback'];
            $params = $this->$beforeCallback($params);
        }

        $api = $params['api'];
        $apiDataKey = $params['key'];
        $apiParams = $params['apiParams'];
        $method = $params['method'];
        $requestParams = $params['requestParams'];

        $data = $this->adapter->api($api, $apiParams, $method, $requestParams);

        $this->lastRawResponse = $data;

        return isset($data[$apiDataKey]) ? $data[$apiDataKey] : null;
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

    public function getData($dataMapKey = null)
    {
        $mapping = $this->dataMapping;
        $data = array();
        if($dataMapKey){
            $this->mapKey = $dataMapKey;
            $dataMapping = $mapping[$dataMapKey];
            foreach($dataMapping['Nodes'] as $key => $dataNode){
                $data[$key] = $this->api($dataNode, $dataMapping);
            }
            return $data;
        }


        foreach($mapping as $dataMapKey => $dataMapping){
            if($dataMapping['Type'] != 'Read'){
                continue;
            }

            foreach($dataMapping['Nodes'] as $key => $dataNode){
                $data[$dataMapKey][$key] = $this->api($dataNode, $dataMapping);
            }
        }
        return $data;
    }

    public function writeData($dataMapKey, $params)
    {
        $apiParams = $this->prepareApiParamsFromMapping($dataMapKey);

        $mapping = $this->dataMapping;
        $dataMapping = $mapping[$dataMapKey];
        $dataNodes = $dataMapping['Nodes'];
        $data = array();
        foreach($dataNodes as $key => $remoteDataKey){
            if(isset($params[$key])){
                $data[$remoteDataKey] = $params[$key];
            }
        }

        $apiParams['requestParams'] = $data;
        $data = $this->api($apiParams, $dataMapping);
        
        $response = $this->lastRawResponse;
        $result = $params;
        if(isset($dataMapping['ResponseNodes'])){
            $responseMapping = $dataMapping['ResponseNodes'];
            foreach($responseMapping as $key => $remoteDataKey){
                if(isset($response[$remoteDataKey])){
                    $result[$key] = $response[$remoteDataKey];
                }
            }
        }
        return $result;
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

    public function getLastRawResponse()
    {
        return $this->lastRawResponse;
    }
}
