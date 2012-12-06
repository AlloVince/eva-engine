<?php
namespace Webservice\Adapter;

use Webservice\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Response;
use Zend\Http\Client;
use Zend\Json\Json;

abstract class AbstractAdapter implements AdapterInterface
{
    const FORMAT_JSON = 'json';
    const FORMAT_JSONP = 'jsonp';
    const FORMAT_XML = 'xml';


    protected $apiUri;

    protected $apiRequestSend = false;

    protected $apiResponse;

    protected $apiResponseValid = false;

    protected $apiResponseSuccess = false;

    /*
    * Final client response data after parse
    */
    protected $apiData;

    protected $apiMap = array();
    protected $apiHost;

    protected $messages;

    protected $options;

    protected $client;

    protected $innerCache;
    
    protected $uniformApi = array();

    protected $successResponseFormat = self::FORMAT_JSON;

    protected $errorResponseFormat = self::FORMAT_JSON;

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    protected $authorityType;
    protected $authorityClass;
    protected $authority;

    /**
    * Set the service locator.
    *
    * @param ServiceLocatorInterface $serviceLocator
    * @return AbstractHelper
    */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
    * Get the service locator.
    *
    * @return \Zend\ServiceManager\ServiceLocatorInterface
    */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function getEventManager()
    {
    
    }


    public function getAdapterKey()
    {
        $className = get_class($this);
        $className = explode('\\', $className);
        return strtolower(array_pop($className));
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setSuccessResponseFormat($format)
    {
        $this->successResponseFormat = $format;
        return $this;
    }

    public function setErrorResponseFormat($format)
    {
        $this->errorResponseFormat = $format;
        return $this;
    }

    public function setUniformApi(array $apiMap)
    {
        foreach($apiMap as $key => $value){
            $this->uniformApi[$key] = $value;
        }

        return $this;
    }

    public function getUniformApi()
    {
        return $this->uniformApi;
    }

    public function api($apiNameOrUrl, $urlParams = null, $method = 'GET', array $requestParams = array())
    {
        $apiUri = '';
        if(0 === strpos($apiNameOrUrl, 'http://') || 0 === strpos($apiNameOrUrl, 'https://')){
            $apiUri = $apiNameOrUrl;
        } elseif(0 === strpos($apiNameOrUrl, '/')) {
            //Start from / will be a url path
            $apiUri = $this->apiHost . $apiNameOrUrl;
        } else {
            list($apiUri, $method) = $this->getApiUriFromMap($apiNameOrUrl);
        }

        //: must after http:
        if(strripos($apiUri, ':') > 5){
            $apiUri = $this->replaceUri($apiUri, $urlParams);
        } else {
            //Nothing need to replace in Url, use second paramater as main
            if(!$requestParams && $urlParams){
                $requestParams = $urlParams;
            }
        }

        $cacheKey = '';
        //Cache complate same GET request in memery
        if($method === 'GET'){
            $cacheKey = md5(serialize(array(
                $apiUri,
                $urlParams,
                $requestParams,
            )));
            if(isset($this->innerCache[$cacheKey])){
                return $this->innerCache[$cacheKey];
            }
        }


        if(!$apiUri){
            throw new Exception\InvalidArgumentException(sprintf(
                'API url not found'
            ));
        }

        $this->setApiUri($apiUri);
        $client = $this->getClient();
        $client->setMethod($method);

        if($requestParams){
            if($method === 'GET'){
                $client->setParameterGet($requestParams);
            } else {
                $client->setParameterPost($requestParams);
            }
        }

        $data = $this->getApiData();
        if($cacheKey){
            $this->innerCache[$cacheKey] = $data;
        }
        return $data;
    }

    public function uniformApi($uniformApiType)
    {
        $uniformApiType = ucfirst(strtolower($uniformApiType));
        $adapter = get_class($this);
        $uniformClass = str_replace('\\Adapter\\', '\\Adapter\\' . $uniformApiType . '\\', $adapter);
        if(!class_exists($uniformClass)){
            throw new Exception\InvalidArgumentException(sprintf(
                'Request uniform api %s not exist', $uniformClass
            ));
        }

        $uniformApi = new $uniformClass();
        $uniformApi->setAdapter($this);
        return $uniformApi;
    }

    public function getApiMap()
    {
        return $this->apiMap;
    }

    public function getApiUriFromMap($apiName)
    {
        $method = 'GET';
        $apiUri = '';
        $host = $this->apiHost;
        $map = $this->apiMap;

        $apiKeys = explode('::', $apiName);
        foreach($apiKeys as $mapKey){
            if(!isset($map[$mapKey])){
                throw new Exception\InvalidArgumentException(sprintf(
                    'Api %s no found in api map in %s', $apiName, get_class($this)
                ));
            }
            $map = $map[$mapKey];
        }

        if(is_string($map)){
            $apiUri = $map;
        } else {
            if(isset($map['method'])){
                $method = $map['method'];
            }
            if(isset($map['url'])){
                $apiUri = $map['url'];
            }
        }
        return array($host . $apiUri, $method);
    }

    protected function replaceUri($uri, $urlParams)
    {
        if(!$urlParams){
            return $uri;
        }

        if(is_string($urlParams)){
            $uri = preg_replace('/:[\w_]+/', $urlParams, $uri);
        } elseif (is_array($urlParams)){
            $patterns = array();
            $replaces = array();
            foreach($urlParams as $key => $value){
                $patterns[] = ":$key";
                $replaces[] = $value;
            }
            $uri = str_replace($patterns, $replaces, $uri);
        }
        return $uri;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    public function getClient()
    {
        if($this->client){
            return $this->client;
        }

        $client = $this->getAuthorityClient();

        if(!$client){
            $client = new Client();
        }
        $client->setOptions(array(
            'sslverifypeer' => false
        ));
        return $this->client = $client;
    }

    protected function getAuthorityClient()
    {
        $authorityType = $this->authorityType;
        $authorityClass = $this->authorityClass;
        if(!$authorityType || !$authorityClass){
            return false;
        }

        $authorityBridge = "Webservice\\Authority\\$authorityType";
        if(false == class_exists($authorityBridge)){
            throw new Exception\InvalidArgumentException(sprintf(
                'No Webservice authority bridge %s found', $authorityBridge
            ));
        }
        $authority = new $authorityBridge($authorityClass, $this->getOptions(), $this->getServiceLocator());
        return $authority->getClient();
    }

    public function setApiUri($uri)
    {
        $this->apiUri = $uri;
        $this->getClient()->setUri($uri);
        $this->apiRequestSend = false;
        $this->apiResponseValid = false;
        $this->apiResponseSuccess = false;
        return $this;
    }

    public function getApiUri()
    {
        return $this->apiUri;
    }

    public function getApiResponse()
    {
        return $this->apiResponse;
    }

    public function sendApiRequest()
    {
        $client = $this->getClient();
        $this->apiResponse = $client->send();
        $this->apiRequestSend = true;

        return $this->apiResponse;
    }

    public function isApiResponseSuccess()
    {
        if(true === $this->apiResponseValid){
            return $this->apiResponseSuccess;
        }

        $response = $this->apiResponse;
        if(!$response){
            $this->messages = 'No API response found';
            return false;
        }

        if(!$response instanceof Response){
            $this->messages = 'API Response type not correct, should be instance of Zend\Http\Response';
            return false;
        }

        if(!$response->isSuccess()){
            return false;
        }

        if($this->isApiResponseHasErrorMessage()){
            return false;
        }

        $this->apiResponseValid = true;
        return $this->apiResponseSuccess = true;
    }

    public function getApiData()
    {
        if(false === $this->apiRequestSend){
            $this->sendApiRequest();
        }

        if(false === $this->apiResponseValid){
            $this->isApiResponseSuccess();
        }

        if(false === $this->apiResponseSuccess){
            return $this->apiData = array();
        }

        $response = $this->apiResponse;
        $format = $this->successResponseFormat;
        $data = $this->parseResponse($response, $format);
        return $this->apiData = $data;
    }

    public function isApiResponseHasErrorMessage()
    {
        $response = $this->apiResponse;
        $responseText = $response->getBody();
		if (strpos($responseText, "error") !== false) {
			return true;
		}
		return false;
    }

    public function getMessages()
    {
        $response = $this->apiResponse;
        if($response && $response instanceof Response){
            $format = $this->errorResponseFormat;
            $data = $this->parseResponse($response, $format);
            return $this->messages = $data;
        } else {
            return $this->messages;
        }
    }

    public function attach($eventName, $callback)
    {
        if(!$this->serviceLocator){
            return $this;
        }
        return $this;
    }

    protected function getDataValueByMappingString($data, $dataKeyString)
    {
        $dataKeyArray = explode('::', $dataKeyString);
        foreach($dataKeyArray as $dataKey){
            if(isset($data[$dataKey])){
                $data = $data[$dataKey];
            } else {
                $data = null;
            }
        }
        return $data;
    }

    public function readMapping(array $data = array(),array $mapping = array())
    {
        if(!$data || !$mapping){
            return $data;
        }

        /*
        * response is {"rsp":{"photoid":"8248454241"}}
        * mapping to id : 8248454241
        * by array('id' => 'rsp::photoid')
        */
        $newData = array();
        foreach($mapping as $key => $dataKeyString){
            $newData[$key] = $this->getDataValueByMappingString($data, $dataKeyString);
        }
        return $newData;
    }

    public function writeMapping(array $data = array(),array $mapping = array())
    {
        if(!$data || !$mapping){
            return $data;
        }
        $newData = array();
        foreach($data as $key => $value){
            if(!isset($mapping[$key])){
                continue;
            }

            $newData[$mapping[$key]] = $value;
        }
        return $newData;
    }

    protected function parseResponse(Response $response, $format)
    {
        switch($format){
            case self::FORMAT_JSON:
            $data = $this->parseJsonResponse($response);
            break;
            case self::FORMAT_JSONP:
            $data = $this->parseJsonpResponse($response);
            break;
            case self::FORMAT_XML:
            $data = $this->parseXmlResponse($response);
            break;
            default:
            throw new Exception\InvalidArgumentException(sprintf('Unsupport response format %s in %s', $format, get_class($this)));
        }

        return $data;
    }

    protected function parseJsonResponse(Response $response)
    {
        $responseText = $response->getBody();
        if(!$responseText){
            return;
        }
        $data = Json::decode($responseText, Json::TYPE_ARRAY);
        return $data;
    }

    protected function parseJsonpResponse(Response $response)
    {
        $responseText = $response->getBody();
        if(!$responseText){
            return;
        }
        $lpos = strpos($responseText, "(");
        $rpos = strrpos($responseText, ")");
        $responseText = substr($responseText, $lpos + 1, $rpos - $lpos -1);
        $data = Json::decode($responseText, Json::TYPE_ARRAY);
        return $data;	
    }

    protected function parseXmlResponse(Response $response)
    {
        $responseText = $response->getBody();
        if(!$responseText){
            return;
        }
        $data = Json::decode(Json::fromXml($responseText), Json::TYPE_ARRAY);
        return $data;
    }
}
