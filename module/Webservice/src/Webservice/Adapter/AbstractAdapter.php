<?php
namespace Webservice\Adapter;

use Webservice\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Response;

abstract class AbstractAdapter implements AdapterInterface
{
    const FORMAT_JSON = 'json';
    const FORMAT_JSONP = 'jsonp';
    const FORMAT_XML = 'xml';


    protected $apiUri;

    protected $apiResponse;

    /*
    * Final client response data after parse
    */
    protected $apiData;

    protected $options;

    protected $client;
    
    protected $uniformApi = array();

    protected $messages;

    protected $successResponseFormat = self::FORMAT_JSON;

    protected $errorResponseFormat = self::FORMAT_JSON;

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

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

    public function api($apiName)
    {
        if(isset($this->uniformApi[$apiName])){
            
        }
    
    }


    public function getClient()
    {
        return $this->client;
    }

    public function setApiUri($uri)
    {
        $this->apiUri = $uri;
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
    }

    public function isApiResponseSuccess()
    {
        $response = $this->apiResponse;
        if(!$response){
            return false;
        }
    }

    public function getApiData()
    {
        $response = $this->apiResponse;
        $format = $this->successResponseFormat;
        $data = $this->parseResponse($response, $format);
        return $this->apiData = $data;
    }


    public function getMessages()
    {
        $response = $this->apiResponse;
        $format = $this->errorResponseFormat;
        $data = $this->parseResponse($response, $format);
        return $this->messages = $data;
    }

    public function attach()
    {
        if(!$this->serviceLocator){
            return $this;
        }
        return $this;
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
        $data = (array) \Zend\Json\Json::decode($response->getBody());
        return $data;
    }

    protected function parseJsonpResponse(Response $response)
    {
    
    }

    protected function parseXmlResponse(Response $response)
    {
    
    }
}
