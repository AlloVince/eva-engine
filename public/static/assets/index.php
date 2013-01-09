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

    protected $options = array(
        'libRootPath' => '',
        'urlRootPath' => '',
        'cache' => false,
        'useSeaJs' => false,
    );

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
        if($fileSourceType == 'lib'){
            $sourcePath = $this->libRootPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $urlPathArray);
            $targetPath = $this->urlRootPath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $urlPathArray);

            $fileAsset = new FileAsset($sourcePath);
            if(true === $this->cache){
                $this->prepareDirectoryStructure($targetPath, count($urlPathArray));
                copy($sourcePath, $targetPath);
            }

            $mimeType = $this->getMimeType($sourcePath);
            header("Content-Type: $mimeType");
            echo $fileAsset->dump();
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
                }
                $mimeType = $this->getMimeType($sourcePath);
                header("Content-Type: $mimeType");
                echo $fileAsset->dump();
            }
        }
    }

    public function run()
    {
        $this->copy();
    }

    public function __construct($config)
    {
        $this->libRootPath = $config['libRootPath'];
        $this->urlRootPath  = $config['urlRootPath'];
        $this->cache = $config['cache'];
    }

    protected function getFileExtension($filePath)
    {
        $fileExt = explode(".", $filePath);
        return strtolower(array_pop($fileExt));
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

        //UrlRewrite not enabled
        if($urlPathArray[0] === 'index.php'){
            array_shift($urlPathArray);
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


    protected function getMimeType($filePath)
    {
        $fileExt = $this->getFileExtension($filePath);

        if(!$fileExt){
            return 'application/octet-stream';
        }

        $mimeTypes = array(
            "ez" => "application/andrew-inset",
            "hqx" => "application/mac-binhex40",
            "cpt" => "application/mac-compactpro",
            "doc" => "application/msword",
            "bin" => "application/octet-stream",
            "dms" => "application/octet-stream",
            "lha" => "application/octet-stream",
            "lzh" => "application/octet-stream",
            "exe" => "application/octet-stream",
            "class" => "application/octet-stream",
            "so" => "application/octet-stream",
            "dll" => "application/octet-stream",
            "oda" => "application/oda",
            "pdf" => "application/pdf",
            "ai" => "application/postscript",
            "eps" => "application/postscript",
            "ps" => "application/postscript",
            "smi" => "application/smil",
            "smil" => "application/smil",
            "wbxml" => "application/vnd.wap.wbxml",
            "wmlc" => "application/vnd.wap.wmlc",
            "wmlsc" => "application/vnd.wap.wmlscriptc",
            "bcpio" => "application/x-bcpio",
            "vcd" => "application/x-cdlink",
            "pgn" => "application/x-chess-pgn",
            "cpio" => "application/x-cpio",
            "csh" => "application/x-csh",
            "dcr" => "application/x-director",
            "dir" => "application/x-director",
            "dxr" => "application/x-director",
            "dvi" => "application/x-dvi",
            "spl" => "application/x-futuresplash",
            "gtar" => "application/x-gtar",
            "hdf" => "application/x-hdf",
            "js" => "application/x-javascript",
            "skp" => "application/x-koan",
            "skd" => "application/x-koan",
            "skt" => "application/x-koan",
            "skm" => "application/x-koan",
            "latex" => "application/x-latex",
            "nc" => "application/x-netcdf",
            "cdf" => "application/x-netcdf",
            "sh" => "application/x-sh",
            "shar" => "application/x-shar",
            "swf" => "application/x-shockwave-flash",
            "sit" => "application/x-stuffit",
            "sv4cpio" => "application/x-sv4cpio",
            "sv4crc" => "application/x-sv4crc",
            "tar" => "application/x-tar",
            "tcl" => "application/x-tcl",
            "tex" => "application/x-tex",
            "texinfo" => "application/x-texinfo",
            "texi" => "application/x-texinfo",
            "t" => "application/x-troff",
            "tr" => "application/x-troff",
            "roff" => "application/x-troff",
            "man" => "application/x-troff-man",
            "me" => "application/x-troff-me",
            "ms" => "application/x-troff-ms",
            "ustar" => "application/x-ustar",
            "src" => "application/x-wais-source",
            "xhtml" => "application/xhtml+xml",
            "xht" => "application/xhtml+xml",
            "zip" => "application/zip",
            "au" => "audio/basic",
            "snd" => "audio/basic",
            "mid" => "audio/midi",
            "midi" => "audio/midi",
            "kar" => "audio/midi",
            "mpga" => "audio/mpeg",
            "mp2" => "audio/mpeg",
            "mp3" => "audio/mpeg",
            "aif" => "audio/x-aiff",
            "aiff" => "audio/x-aiff",
            "aifc" => "audio/x-aiff",
            "m3u" => "audio/x-mpegurl",
            "ram" => "audio/x-pn-realaudio",
            "rm" => "audio/x-pn-realaudio",
            "rpm" => "audio/x-pn-realaudio-plugin",
            "ra" => "audio/x-realaudio",
            "wav" => "audio/x-wav",
            "pdb" => "chemical/x-pdb",
            "xyz" => "chemical/x-xyz",
            "bmp" => "image/bmp",
            "gif" => "image/gif",
            "ief" => "image/ief",
            "jpeg" => "image/jpeg",
            "jpg" => "image/jpeg",
            "jpe" => "image/jpeg",
            "png" => "image/png",
            "tiff" => "image/tiff",
            "tif" => "image/tif",
            "djvu" => "image/vnd.djvu",
            "djv" => "image/vnd.djvu",
            "wbmp" => "image/vnd.wap.wbmp",
            "ras" => "image/x-cmu-raster",
            "pnm" => "image/x-portable-anymap",
            "pbm" => "image/x-portable-bitmap",
            "pgm" => "image/x-portable-graymap",
            "ppm" => "image/x-portable-pixmap",
            "rgb" => "image/x-rgb",
            "xbm" => "image/x-xbitmap",
            "xpm" => "image/x-xpixmap",
            "xwd" => "image/x-windowdump",
            "ico" => "image/x-icon",
            "igs" => "model/iges",
            "iges" => "model/iges",
            "msh" => "model/mesh",
            "mesh" => "model/mesh",
            "silo" => "model/mesh",
            "wrl" => "model/vrml",
            "vrml" => "model/vrml",
            "css" => "text/css",
            "html" => "text/html",
            "htm" => "text/html",
            "asc" => "text/plain",
            "txt" => "text/plain",
            "rtx" => "text/richtext",
            "rtf" => "text/rtf",
            "sgml" => "text/sgml",
            "sgm" => "text/sgml",
            "tsv" => "text/tab-seperated-values",
            "wml" => "text/vnd.wap.wml",
            "wmls" => "text/vnd.wap.wmlscript",
            "etx" => "text/x-setext",
            "xml" => "text/xml",
            "xsl" => "text/xml",
            "mpeg" => "video/mpeg",
            "mpg" => "video/mpeg",
            "mpe" => "video/mpeg",
            "qt" => "video/quicktime",
            "mov" => "video/quicktime",
            "mxu" => "video/vnd.mpegurl",
            "avi" => "video/x-msvideo",
            "movie" => "video/x-sgi-movie",
            "ice" => "x-conference-xcooltalk"
        ); 

        return isset($mimeTypes[$fileExt]) ? $mimeTypes[$fileExt] : 'application/octet-stream'; 
    }


}

$config = include __DIR__ . '/config.inc.php';
$configLocalFile = EVA_CONFIG_PATH . '/local.front.assets.config.php';
$configLocal = is_file($configLocalFile) ? include $configLocalFile : array();
$config = $configLocal ? array_merge($config, $configLocal) : $config;
$asset = new EvaAssets($config);
$asset->run();
