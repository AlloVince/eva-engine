<?php
namespace Eva;

use Zend\Di\Di,
    Zend\Di\Configuration as DiConfiguration,
    Zend\Db\Adapter\Adapter as DbAdapter,
    Eva\Core\Exception\RuntimeException;
class Api
{
    protected static $instance;
    protected $event;
    protected $config;
    protected $dbAdapter;
    protected $moduleLoaded;
    protected $di;

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Shorthand for getInstance
     *
     * @return Eva\Api
     */
    public static function _()
    {
        return self::getInstance();
    }

    public static function getInstance()
    {
        if( is_null(self::$instance) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Set or unset the current api instance
     * 
     * @param Eva\Api $api
     * @return Eva\Api
     */
    public static function setInstance(Api $api = null) 
    {
        return self::$instance = $api;
    }

    public function getConfig()
    {
        $event = $this->getEvent();
        $app = $event->getParam('application');
        return $app->getConfiguration();
    }

    public function setConfig($config)
    {
    }

    public function getAppConfig()
    {
    }


    public function getModuleLocalConfig()
    {
    }

    public function getModuleLoaded()
    {
        if($this->moduleLoaded){
            return $this->moduleLoaded;
        }
        
        $event = $this->getEvent();
        $modules = $event->getParam('application')->getServiceManager()->get('modulemanager')->getLoadedModules();
        $moduleLoaded = array_keys($modules);
        return $this->moduleLoaded = $moduleLoaded;
    }


    public function isModuleLoaded($className)
    {
        $moduleLoaded = $this->getModuleLoaded();

        $className = ltrim($className, '\\');
        $moduleName = explode('\\', $className);
        if(!$moduleName) {
            return false;
        }

        if(isset($moduleName[0]) && false === in_array($moduleName[0], $moduleLoaded)){
            return false;
        }

        return true;
    }

    public function getDi()
    {
        if($this->di){
            return $this->di;
        }


        return $this->di = new Di();
    }

    public function setDbAdapter($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return self::$instance;
    }

    public function getDbAdapter($configKeyOrArray = 'db')
    {
        if($this->dbAdapter) {
            return $this->dbAdapter;
        }


        $dbAdapter = array();
        if(true === is_array($configKeyOrArray)){
            $dbAdapter = new DbAdapter($configKeyOrArray);
        } else {
            $configKey = $configKeyOrArray;
            $config = $this->getConfig();
            if(isset($config[$configKey]) && $config[$configKey]){
                $dbAdapter = new DbAdapter($config[$configKey]);
            }
        }

        return $this->dbAdapter = $dbAdapter;
    }

    public function getDbTable($tableClassName)
    {
        if(false === $this->isModuleLoaded($tableClassName)){
            throw new RuntimeException(sprintf(
                'Module not loaded by class %s',
                $tableClassName
            ));    
        }

        //TODO :: Use Di here
        return new $tableClassName($this->getDbAdapter());
    }

    public function getForm($formClassName)
    {
        if(false === $this->isModuleLoaded($formClassName)){
            throw new RuntimeException(sprintf(
                'Module not loaded by class %s',
                $formClassName
            ));    
        }

        return new $formClassName;
    }

    public function getModel($modelClassName, array $diConfig = array())
    {
        if(false === $this->isModuleLoaded($modelClassName)){
            throw new RuntimeException(sprintf(
                'Module not loaded by class %s',
                $modelClassName
            ));    
        }

        if($diConfig){
            $di = $this->getDi();
            $di->configure(new DiConfiguration($diConfig));
            //$di->instanceManager()->setParameters($modelClassName, $diConfig);
            //\Zend\Di\Display\Console::export($di);

            return $di->get($modelClassName);
        }
        

        return new $modelClassName;
    }

    public function getView()
    {
    }
}
