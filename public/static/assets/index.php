<?php
error_reporting(E_ALL);
// Check version
if( version_compare(phpversion(), '5.3.3', '<') ) {
  printf('PHP 5.3.3 is required, you have %s', phpversion());
  exit(1);
}

defined('EVA_ROOT_PATH')    || define('EVA_ROOT_PATH', __DIR__ . '/../../..');
defined('EVA_PUBLIC_PATH')    || define('EVA_PUBLIC_PATH', __DIR__ . '/../..');
defined('EVA_LIB_PATH')    || define('EVA_LIB_PATH', __DIR__ . '/../../../vendor');
defined('EVA_MODULE_PATH')    || define('EVA_MODULE_PATH', __DIR__ . '/../../../module');
defined('EVA_CONFIG_PATH')    || define('EVA_CONFIG_PATH', __DIR__ . '/../../../config');

/** Public functions */
function p($r, $usePr = false)
{
    if($usePr || false === method_exists('\Zend\Debug','dump')){
        echo '<pre>' . print_r($r, true) . '</pre>';
        return;
    }
    \Zend\Debug::dump($r);
}

require EVA_LIB_PATH . '/Zend/library/Zend/Loader/AutoloaderFactory.php';
use Zend\Loader\AutoloaderFactory;
AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true
    )
));

$loader = AutoloaderFactory::getRegisteredAutoloaders();
$loader = $loader[AutoloaderFactory::STANDARD_AUTOLOADER];
$loader->registerNamespace('Eva\\', EVA_LIB_PATH . '/Eva/');
$loader->registerNamespace('Assetic\\', EVA_LIB_PATH . '/Assetic/src/Assetic/');
$loader->registerNamespace('Symfony\\', EVA_LIB_PATH . '/Symfony/');

$appGlobelConfig = include EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.config.php';
$appLocalConfig = EVA_CONFIG_PATH . DIRECTORY_SEPARATOR . 'application.local.config.php';
if(file_exists($appLocalConfig)){
    $appLocalConfig = include $appLocalConfig;
    $appGlobelConfig = array_merge($appGlobelConfig, $appLocalConfig);
}
Zend\Mvc\Application::init($appGlobelConfig);


use Assetic\AssetManager;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\AssetReference;
use Assetic\FilterManager;
use Assetic\Filter\Sass\SassFilter;
use Assetic\Filter\Yui;
use Assetic\Factory\AssetFactory;
use Assetic\Filter\GoogleClosure\CompilerApiFilter;
use Assetic\AssetWriter;
use Assetic\Factory\Worker\CacheBustingWorker;

class EvaAssets
{

    protected $cache = false;

    protected $libRootPath;

    protected $urlRootPath;

    protected $modulePath;

    protected $defines = array(
        //JS
        'jquery' =>  '/js/jquery/jquery.js',
        'bootstrap_js' => '/js/bootstrap/bootstrap.js',
        //CSS
        'bootstrap' => '/css/bootstrap/bootstrap.css',
    );

    protected $assetManager;

    protected $filter;

    protected $writer;

    public function getDefaultFilter()
    {
    
    }

    public function getDefaultWriter()
    {
        if($this->writer){
            return $this->writer;
        }

        return $this->writer = new AssetWriter(__DIR__);
    
    }

    public function getAssetManager()
    {
        if($this->assetManager){
            return $this->assetManager;
        }

        return $this->assetManager = new AssetManager();
    }

    public function compress()
    {
        $am = new AssetManager();
        $am->set('jquery', new FileAsset($jsPath . '/jquery/jquery.js'));
        $am->set('base_css', new GlobAsset($cssPath . '/bootstrap/bootstrap.css'));
        $am->set('jquery_anytime', new AssetCollection(array(
            new AssetReference($am, 'jquery'),
            new FileAsset($jsPath . '/jquery/jquery.anytime.js'),
        )));

        $fm = new FilterManager();
        $fm->set('yui_js', new Yui\JsCompressorFilter(__DIR__ . '/yuicompressor.jar', 'C:\Program Files\Java\jdk1.7.0_09\bin\java.exe'));

        $factory = new AssetFactory(__DIR__);
        $factory->setAssetManager($am);
        $factory->setFilterManager($fm);
        $factory->setDebug(true);

        $js = $factory->createAsset(array(
            '@jquery_anytime',         // load the asset manager's "reset" asset
        ), array(
            //'yui_js', 
        ), array(
            'output' => 'all.js'
        ));

        $writer = new AssetWriter(__DIR__);
        $writer->writeAsset($js);
        //$css->setTargetPath(ASSETS);

        //$js->dump();

    }

    public function copy()
    {
        $urlPathArray = $this->getUrlPathArray();

        $fileSourceType = array_shift($urlPathArray);
        if($fileSourceType == 'lib' || $fileSourceType == 'eva'){
            $sourcePath = $this->libRootPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $urlPathArray);
            $targetPath = $this->urlRootPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $urlPathArray);

            $fileAsset = new FileAsset($sourcePath);
            if(true === $this->cache){
                $this->prepareDirectoryStructure($targetPath, count($urlPathArray));
                copy($sourcePath, $targetPath);
            } else {
                header('Content-Type: text/css');
                echo $fileAsset->dump();
            }
        } elseif($fileSourceType == 'module') {
            $module = array_shift($urlPathArray);
            $moduleClass = ucfirst($module) . '\\' . 'Module';
            if(true === class_exists($moduleClass)){
                $object = new \ReflectionObject(new $moduleClass);
                $modulePath = dirname($object->getFileName());
                $moduleAssetPath = $modulePath . DIRECTORY_SEPARATOR . 'assets';

                $sourcePath = $moduleAssetPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $urlPathArray);
                $targetPath = $this->urlRootPath . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $urlPathArray);

                $fileAsset = new FileAsset($sourcePath);

                if(true === $this->cache){
                    $this->prepareDirectoryStructure($targetPath, count($urlPathArray));
                    copy($sourcePath, $targetPath);
                } else {
                    header('Content-Type: text/css');
                    echo $fileAsset->dump();
                }
            }
        }
    }

    public function run()
    {
        $this->copy();
    }

    public function __construct()
    {
        $this->libRootPath = realpath(__DIR__ . '/../lib');
        $this->urlRootPath  = __DIR__;
    }

    protected function getUrlPathArray()
    {
        $url = $this->getCurrentUrl();
        $url = parse_url($url);
        $urlPath = ltrim($url['path'], '/');

        $rootPathArray = explode(DIRECTORY_SEPARATOR, $this->urlRootPath);
        $urlPathArray = explode('/', $urlPath);
        $rootPathArrayCount = count($rootPathArray);
        $urlPathArrayCount = count($urlPathArray);

        $prefixArray = array();
        for($i = 0; $i < $rootPathArrayCount; $i++){
            $subRootArray = array_slice($rootPathArray, $i, $rootPathArrayCount);

            $subRootArrayCount = count($subRootArray);
            if($subRootArrayCount > $urlPathArrayCount){
                continue;
            }

            $subUrlArray = array_slice($urlPathArray, 0, $subRootArrayCount);

            if($subUrlArray === $subRootArray){
                $prefixArray = $subUrlArray;
                break;
            }
        }

        if($prefixArray){
            $urlPathArray = array_slice($urlPathArray, count($prefixArray));
        }

        return $urlPathArray;
    }

    protected function getCurrentUrl()
    {
        $pageURL = 'http';

        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80"){
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }
        else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /**
    * Prepares a directory structure for the given file(spec)
    * using the configured directory level.
    * this method is from https://github.com/zendframework/zf2/blob/master/library/Zend/Cache/Storage/Adapter/Filesystem.php 
    *
    * @param string $file
    * @return void
    */
    protected function prepareDirectoryStructure($file, $level = '')
    {
        if (!$level) {
            return;
        }

        // Directory structure already exists
        $pathname = dirname($file);
        if (file_exists($pathname)) {
            return;
        }

        $perm     = 0700;
        $umask    = false;

        if ($umask !== false && $perm !== false) {
            $perm = $perm & ~$umask;
        }

        if ($perm === false || $level == 1) {
            // build-in mkdir function is enough

            $umask = ($umask !== false) ? umask($umask) : false;
            $res   = mkdir($pathname, ($perm !== false) ? $perm : 0777, true);

            if ($umask !== false) {
                umask($umask);
            }

            if (!$res) {
                $oct = ($perm === false) ? '777' : decoct($perm);
                throw new Exception(
                    "mkdir('{$pathname}', 0{$oct}, true) failed", 0, $err
                );
            }

            if ($perm !== false && !chmod($pathname, $perm)) {
                $oct = decoct($perm);
                throw new Exception(
                    "chmod('{$pathname}', 0{$oct}) failed", 0, $err
                );
            }

        } else {
            // build-in mkdir function sets permission together with current umask
            // which doesn't work well on multo threaded webservers
            // -> create directories one by one and set permissions

            // find existing path and missing path parts
            $parts = array();
            $path  = $pathname;
            while (!file_exists($path)) {
                array_unshift($parts, basename($path));
                $nextPath = dirname($path);
                if ($nextPath === $path) {
                    break;
                }
                $path = $nextPath;
            }

            // make all missing path parts
            foreach ($parts as $part) {
                $path.= DIRECTORY_SEPARATOR . $part;

                // create a single directory, set and reset umask immediatly
                $umask = ($umask !== false) ? umask($umask) : false;
                $res   = mkdir($path, ($perm === false) ? 0777 : $perm, false);
                if ($umask !== false) {
                    umask($umask);
                }

                if (!$res) {
                    $oct = ($perm === false) ? '777' : decoct($perm);
                    throw new Exception(
                        "mkdir('{$path}', 0{$oct}, false) failed"
                    );
                }

                if ($perm !== false && !chmod($path, $perm)) {
                    $oct = decoct($perm);
                    throw new Exception(
                        "chmod('{$path}', 0{$oct}) failed"
                    );
                }
            }
        }
    }

}

$asset = new EvaAssets();
$asset->run();
