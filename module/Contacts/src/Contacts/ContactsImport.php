<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Contacts_Service
 * @author    AlloVince
 */

namespace Contacts;

/**
 * Base class for all protocols supporting tree
 *
 * @category   Core
 * @package    Core_Tree_Tree
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD Licens
 */
class ContactsImport
{
    /**
     * Array holding all directions
     *
     * @var array
     */
    protected $adapter = array();
    
    protected $storage;
    
    protected $adapterName;
    
    /**
     * Creates a file processing handler
     *
     * @param  string  $adapter   Adapter to use
     * @param  boolean $direction OPTIONAL False means Download, true means upload
     * @param  array   $options   OPTIONAL Options to set for this adapter
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($adapter = 'Google', $direction = false, $options = array())
    {
        $this->adapterName = $adapter;
        $this->setAdapter($adapter, $direction, $options);
        $storage = isset($options['storage']) ? $options['storage'] : 'cache';
        $storage = $this->initStorage($storage, $options['cacheConfig']);
    }

    /**
     * Sets a new adapter
     *
     * @param  string  $adapter   Adapter to use
     * @param  boolean $direction OPTIONAL False means Download, true means upload
     * @param  array   $options   OPTIONAL Options to set for this adapter
     * @return Transfer
     * @throws Exception\InvalidArgumentException
     */
    public function setAdapter($adapter, $direction = false, $options = array())
    {
        if (!is_string($adapter)) {
            throw new Exception\InvalidArgumentException('Adapter must be a string');
        }
          
        if ($adapter[0] != '\\') {
            $adapter = '\Contacts\Import\\' . ucfirst($adapter);
        }
        
        $direction = (integer) $direction;
        $this->adapter[$direction] = new $adapter($options);
        if (!$this->adapter[$direction] instanceof Import\AbstractAdapter) {
            throw new Exception\InvalidArgumentException(
                'Adapter ' . $adapter . ' does not extend Contacts\Import\AbstractAdapter'
            );
        }

        return $this;
    }
    
    /**
     * Returns all set adapters
     *
     * @param boolean $direction On null, all directions are returned
     *                           On false, download direction is returned
     *                           On true, upload direction is returned
     * @return array|Adapter\AbstractAdapter
     */
    public function getAdapter($direction = null)
    {
        if ($direction === null) {
            return $this->adapter;
        }

        $direction = (integer) $direction;
        return $this->adapter[$direction];
    }

    public function initStorage($storageName, $config)
    {
        $storageClass = 'Contacts\Storage\\' . ucfirst(strtolower($storageName));
        if(false === class_exists($storageClass)){
            throw new Exception\InvalidArgumentException(sprintf('Undefined oauth storage %s', $storageName));
        }
        return $this->storage = new $storageClass($this->adapterName, $config);
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
            $this->setStorage(new Storage\Cache());
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

    /**
     * Calls all methods from the adapter
     *
     * @param  string $method  Method to call
     * @param  array  $options Options for this method
     * @return mixed
     */
    public function __call($method, array $options)
    {
        if (array_key_exists('direction', $options)) {
            $direction = (integer) $options['direction'];
        } else {
            $direction = 0;
        }

        if (method_exists($this->adapter[$direction], $method)) {
            return call_user_func_array(array($this->adapter[$direction], $method), $options);
        }

        throw new Exception\BadMethodCallException("Unknown method '" . $method . "' called!");
    }
}
