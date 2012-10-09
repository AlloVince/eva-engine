<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Core;

use Eva\Api;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Config\Config;
use Zend\Di\Di;
use Zend\Di\Config as DiConfig;
use Zend\Json\Json;

/**
 * Core Authentication
 *
 * @category   Core
 * @package    Core_Auth
 */
class Auth
{
    const STORAGE_NAMESPACE = 'Eva_Auth';

    protected $authService;

    protected $authAdapter;

    protected $authResult;

    protected $authStorage;

    protected $adapterClass;
    protected $storageClass;

    protected $diConfig = array();

    protected $adapterMap = array(
        'Config' => 'Eva\Authentication\Adapter\Config',
        'Digest' => 'Zend\Authentication\Adapter\Digest',
        'DbTable' => 'Zend\Authentication\Adapter\DbTable',
        'Http' => 'Zend\Authentication\Adapter\Http',
        'Ldap' => 'Zend\Authentication\Adapter\Ldap',
    );

    protected $storageMap = array(
        'Session' => 'Zend\Authentication\Storage\Session'
    );

    public static function factory()
    {
        $config = Api::_()->getConfig();
        if(isset($config['authentication']['default_adapter']) && $config['authentication']['default_adapter']
            && isset($config['authentication']['default_storage']) && $config['authentication']['default_storage']) {
                return new static($config['authentication']['default_adapter'], $config['authentication']['default_storage']);
        }

        throw new Exception\InvalidConfigException(sprintf(
            'Authentication adapter or storage not defined in config'
        ));
    }

    public static function getLoginUser()
    {
        $auth = self::factory();
        $user = $auth->getAuthStorage()->read();
        if(!$user){
            return false;
        }
        return (array) Json::decode($user);
    }

    public function saveLoginUser($user)
    {
        return $this->getAuthStorage()->write(Json::encode($user));
    }

    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    public function getAuthService($params = null, $config = null)
    {
        if($this->authService) {
            return $this->authService;
        }

        $this->initAdapter($params);
        $this->initStorage();

        $diConfig = $this->getDiConfig();
        if($config) {
            $diConfig = $this->merge($diConfig, $config);
        }

        $di = new Di();
        $di->configure(new DiConfig($diConfig));
        $this->authService = $authService = $di->get('Zend\Authentication\AuthenticationService');
        return $authService;
    }

    public function getAuthAdapter()
    {
        if($this->authService){
            return $this->authService->getAdapter();
        }
    }

    public function setAuthAdapter(AdapterInterface $adapter)
    {
        $this->authAdapter = $adapter;
        return $this;
    }

    public function getAuthResult()
    {
        return $this->authResult;
    }

    public function getAuthStorage()
    {
        if($this->authService){
            return $this->authService->getStorage();
        }

        if($this->authStorage) {
            return $this->authStorage;
        }

        $this->initStorage();
        $diConfig = $this->getDiConfig();
        $di = new Di();
        $di->configure(new DiConfig($diConfig));
        return $this->authStorage = $di->get($this->storageClass);
    }

    public function setAuthStorage($authStorage)
    {
        $this->authStorage = $authStorage;
        return $this;
    }

    public function setDiConfig(DiConfig $diConfig)
    {
        $this->diConfig = $diConfig;
        return $this;
    }

    public function getDiConfig(array $config = array())
    {
        if($this->diConfig && !$config) {
            return $this->diConfig;
        }
        return $this->diConfig = $this->merge($this->diConfig, $config);
    }

    public function getStorage(array $config = array())
    {
        if($this->authService){
            return $this->authService->getStorage();
        }
    }

    public function authenticate($params, array $config = array())
    {
        $authService = $this->getAuthService($params, $config);
        return $authService->getAdapter()->authenticate();
    }

    public function __construct($adapterName = null, $storageName = null)
    {
        $adapterClass = 'Zend\Authentication\Adapter\DbTable';
        if($adapterName){
            $adapterClass = isset($this->adapterMap[$adapterName]) ? $this->adapterMap[$adapterName] : null;
            if(!$adapterClass){
                throw new Exception\UnauthorizedException(sprintf(
                    'Input authentication adapter %s not defined', $adapterName
                ));
            }
        }
        $this->adapterClass = $adapterClass;

        $storageClass = 'Zend\Authentication\Storage\Session';
        if($storageName){
            $storageClass = isset($this->storageMap[$storageName]) ? $this->storageMap[$storageName] : null;
            if(!$storageClass){
                throw new Exception\UnauthorizedException(sprintf(
                    'Input authentication storage %s not defined', $storageName
                ));
            }
        }
        $this->storageClass = $storageClass;

        $this->diConfig = array('instance' => array(
            'Zend\Authentication\AuthenticationService' => array(
                'parameters' => array(
                    'storage'              => $storageClass,
                    'adapter'              => $adapterClass
                )
            ),
        ));
    }

    protected function initStorage()
    {
        $diConfig = $this->getDiConfig();
        $storageClass = $this->storageClass;
        switch($storageClass){
            default:
            $diConfig['instance']['Zend\Authentication\Storage\Session'] = array(
                'parameters' => array(
                    'namespace'  => self::STORAGE_NAMESPACE,
                ),
            );
        }
    
        $this->diConfig = $diConfig;
        return $this;
    }

    protected function initAdapter($params)
    {
        $diConfig = $this->getDiConfig();
        $adapterClass = $this->adapterClass;

        switch($adapterClass) {
            case 'Eva\Authentication\Adapter\Config':
            $config = Api::_()->getConfig();
            $params = array_merge($params, array(
                'configArray'  => $config['superadmin'],
            ));
            break;
            case 'Zend\Authentication\Adapter\DbTable':
            $config = Api::_()->getConfig();
            if(isset($params['tableName'])){
                $params['tableName'] = $config['db']['prefix'] . $params['tableName'];
            }
            /*
            if(isset($params['identity'])){
                $diConfig['instance'][$adapterClass]['setIdentify'] = array(
                    'value' => $params['identity']
                );
            }
            if(isset($params['credential'])){
                $diConfig['instance'][$adapterClass]['setCredential'] = array(
                    'credential' => $params['credential']
                );
            }
            */
            $params = array_merge($params, array(
                'zendDb'  => Api::_()->getDbAdapter()
            ));
            break;
            default:;
        }

        $diConfig['instance'][$adapterClass]['parameters'] = $params;
        $this->diConfig = $diConfig;
        return $this;
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
