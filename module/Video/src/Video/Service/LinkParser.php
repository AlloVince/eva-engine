<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Authentication
 */

namespace Video\Service;


use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Video\Service\Adapter\AdapterInterface;
use Video\Exception;
use Zend\Validator\Uri as UriValidator;

/**
* @category   Video
* @package    Video
*/
class LinkParser
{
    /**
    * Video adapter
     *
     * @var Adapter\AdapterInterface
     */
    protected $adapter = null;

    protected $adapters = array(
        'v.youku.com' => 'Youku',
        'v.ku6.com' => 'Kuliu',
        'www.tudou.com' => 'Tudou',
        'www.56.com' => 'Wuliu',
        'video.sina.com.cn' => 'Sina',
        'www.letv.com' => 'Letv',
        'www.youtube.com' => 'Youtube',
    );

    protected $options;

    protected $url;

    public static function factory($url)
    {
        $video = new static();
        $validator = new UriValidator();
        if(!$validator->isValid($url)){
            throw new Exception\InvalidArgumentException(sprintf(
                'Input url format not correct'
            ));
        }

        $video->setUrl($url);
        $urlHandler = $validator->getUriHandler();
        $host = strtolower($urlHandler->getHost());

        $adapters = $video->getAdapters();
        if(!isset($adapters[$host])){
            throw new Exception\BadMethodCallException(sprintf(
                'No video adapter found by host %s', $host
            ));
        }

        $adapterName = $adapters[$host];

        $adapterClass = false === strpos($adapterName, '\\') ? 
            'Video\Service\Adapter\\' . $adapterName :
            $adapterName;

        $video->setAdapter(new $adapterClass($url));

        return $video;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function addAdapters($adapters)
    {
        $this->adapters = array_merge($this->adapters, $adapters);
        return $this;
    }

    public function getAdapters()
    {
        return $this->adapters;
    }

     /**
     * Returns the authentication adapter
     *
     * The adapter does not have a default if the storage adapter has not been set.
     *
     * @return Adapter\AdapterInterface|null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Sets the authentication adapter
     *
     * @param  Adapter\AdapterInterface $adapter
     * @return AuthenticationService Provides a fluent interface
     */
    public function setAdapter(Adapter\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
    * Calls all methods from the adapter
    *
    * @param  string $method  Method to call
    * @param  array  $options Options for this method
    * @throws Exception\BadMethodCallException if unknown method
    * @return mixed
    */
    public function __call($method, $options)
    {
        if (method_exists($this->adapter, $method)) {
            return call_user_func_array(array($this->adapter, $method), $options);
        }

        throw new Exception\BadMethodCallException("Unknown method '" . $method . "' called!");
    }
}
