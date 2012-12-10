<?php
namespace Core;

use Eva\Api;
use Eva\Locale\Locale;
use Zend\Mvc\MvcEvent;
use Zend\Console\Request as ConsoleRequest;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements ConsoleBannerProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'ZendQueue' => __DIR__ . '/../../vendor/ZendQueue/library/ZendQueue',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * This method is defined in ConsoleBannerProviderInterface
     */
    public function getConsoleBanner(Console $console){
        return
            "==------------------------------------------------------==\n" .
            "        Welcome to EvaEngine Console                      \n" .
            "==------------------------------------------------------==\n" .
            "Version 0.0.1\n"
        ;
    }

    /**
     * This method is defined in ConsoleUsageProviderInterface
     */
    public function getConsoleUsage(Console $console){
        return array(
            'queue <queueName>'             => 'Run a queue',
        );
    }


    public function onBootstrap($e)
    {
        Api::_()->setEvent($e);
        $event = $e->getApplication()->getEventManager();

        if(!Api::_()->isModuleLoaded('User')){
            $event->attach(MvcEvent::EVENT_DISPATCH, array($this, 'authority'), 100);
        } else {
            $event->attach(MvcEvent::EVENT_DISPATCH, array('User\Module', 'authority'), 100);
        }

        $this->language($e);
    }

    public function language($e)
    {
        $request = $e->getRequest();
        if($request instanceof ConsoleRequest){
            return $this;
        }

        $cookie = $request->getHeaders()->get('cookie');
        $language = isset($cookie->lang) ? $cookie->lang : '';
        $config = $e->getApplication()->getConfig();
        if(!$language){
            if(isset($config['translator']['auto_switch']) && $config['translator']['auto_switch']){
                return $this->autoLanguage($e);
            }
            return $this;
        }
        $config['translator']['locale'] = $language;
        $mm = $e->getApplication()->getServiceManager()->get('ModuleManager');
        $mm->getEvent()->getParam('configListener')->setMergedConfig($config);

        return $this;
    }

    protected function autoLanguage($e)
    {
        $config = $e->getApplication()->getConfig();
        $lang = Locale::getBrowser();
        if(!$lang){
            return $this;
        }
        
        $lang = array_keys($lang);
        $lang = array_shift($lang);
        $subLang = explode('_', $lang);
        if(isset($subLang[1]) 
            && isset($config['translator']['sub_languages'])
            && is_array($config['translator']['sub_languages'])
            && in_array($subLang[1], $config['translator']['sub_languages']))
        {
            $lang = $lang;    
        } else {
            //if sub_languages not defined, use default main-language. e.g. : zh_CN => zh
            $lang = $subLang[0];
        }

        $config['translator']['locale'] = $lang;
        $mm = $e->getApplication()->getServiceManager()->get('ModuleManager');
        $mm->getEvent()->getParam('configListener')->setMergedConfig($config);
        return $this;
    }

    public function authority($e)
    {
        $router = $e->getRouteMatch();
        $moduleNamespace = $router->getParam('moduleNamespace');
        if($moduleNamespace != 'admin'){
            return false;
        }

        $controller = $router->getParam('controllerName');
        $action = $router->getParam('action');

        //No authority pages : login form | login post | logout
        //TODO : add no authority page to config file
        if($controller == 'core' && $action == 'index' || $controller == 'logout' && $action == 'index' || $controller == 'login' && $action = 'index'){
            return;
        }

        $auth = new Auth('Config', 'Session', 'Auth_Admin');
        $isAuthed = $auth->getAuthStorage()->read();
        if(!$isAuthed){
            $application = $e->getApplication();
            $event = $application->getEventManager();
            $errorController = 'Core\Admin\Controller\ErrorController';

            $router->setParam('controller', $errorController);
            $router->setParam('action', 'index');
            $controllerLoader = $application->getServiceManager()->get('ControllerLoader');
            $controllerLoader->setInvokableClass($errorController, $errorController);

            /*
            $e->setError(\Core\Admin\Controller\ErrorController::ERROR_UNAUTHORIZED)
            ->setController($errorController)
            ->setControllerClass($errorController);
            */
            //$results = $event->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);


            /*
            throw new Exception\UnauthorizedException(printf(
                'Unauthorized admin resource'
            ));
            */
        }
    }
}
