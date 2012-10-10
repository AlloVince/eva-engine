<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Authentication
 */

namespace Oauth;

/**
 * @category   Zend
 * @package    Oauth
 */
class OauthService
{
    const VERSION_OAUTH1 = 'Oauth1';
    const VERSION_OAUTH2 = 'Oauth2';

    /**
     * Persistent storage handler
     *
     * @var Storage\StorageInterface
     */
    protected $storage = null;

    /**
     * Authentication adapter
     *
     * @var Adapter\AdapterInterface
     */
    protected $adapter = null;

    protected $oauthVersion = 'Oauth2';

    protected $options;

    /**
     * Constructor
     *
     */
     public static function factory(array $options)
     {
         $defaultOptions = array(
             'adapter' => '',
             'storage' => 'Session',
             'version' => self::VERSION_OAUTH2,
             'callback' => '',
         );
         $options = array_merge($defaultOptions, $options);

         $callback = $options['callback'];
         $version = $options['version'];
         $storage = $options['storage'];
         $adapter = $options['adapter'];

         if(!$callback){
             throw new Exception\InvalidArgumentException(sprintf(
                'No oauth callback url found'
             ));
         }

         $oauth = new static();
         $oauth->setOauthVersion($version);

         $adapter = strtolower($options['adapter']);
         $version = strtolower($version);

         
         $config = \Eva\Api::_()->getConfig();
         $options = array(
             'enable' => true,
             'consumer_key' => '',
             'consumer_secret' => '',
         );
         if(isset($config['oauth'][$version][$adapter])){
             $options = array_merge($options, $config['oauth'][$version][$adapter]);
         }

         if(!$options['enable']){
             throw new Exception\RuntimeException(sprintf(
                'Oauth service %s not enabled by config', get_class($this)
             ));
         }

         $options['consumerKey'] = $options['consumer_key'];
         $options['consumerSecret'] = $options['consumer_secret'];
         $options['callbackUrl'] = $callback;
         unset($options['consumer_key']);
         unset($options['consumer_secret']);
         $oauth->setOptions($options);

         $adapter = $oauth->initAdapter($adapter, $version);

         return $oauth;
     }

     public function getOptions()
     {
         return $this->options;
     }

     public function setOptions($options)
     {
        $this->options = $options;
        return $this;
     }

     public function getOauthVersion()
     {
        return $this->oauthVersion;
     }

     public function setOauthVersion($version)
     {
         if(!$version == self::VERSION_OAUTH2 && !$version == self::VERSION_OAUTH1){
             throw new Exception\InvalidArgumentException(sprintf(
                'Undefined oauth version. Oauth version only allow : %s or %s'
                , self::VERSION_OAUTH2, self::VERSION_OAUTH1)); 
         }
         $this->oauthVersion = $version;
         return $this;
     }

     public function initAdapter($adapterName, $oauthVersion)
     {
         $options = $this->getOptions();

         $adapterClass = 'Oauth\Adapter\\' . $oauthVersion . '\\' . ucfirst(strtolower($adapterName));

         if(false === class_exists($adapterClass)){
            //throw new Exception\InvalidArgumentException(sprintf('Undefined oauth adapter %s by oauth version %s', $adapterName, $oauthVersion));
         }
        
         return $this->adapter = new $adapterClass($options);
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
     * Returns the persistent storage handler
     *
     * Session storage is used by default unless a different storage adapter has been set.
     *
     * @return Storage\StorageInterface
     */
    public function getStorage()
    {
        if (null === $this->storage) {
            $this->setStorage(new Storage\Session());
        }

        return $this->storage;
    }

    /**
     * Sets the persistent storage handler
     *
     * @param  Storage\StorageInterface $storage
     * @return AuthenticationService Provides a fluent interface
     */
    public function setStorage(Storage\StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }


}
