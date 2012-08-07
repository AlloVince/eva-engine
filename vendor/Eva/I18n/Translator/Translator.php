<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_I18n
 */

namespace Eva\I18n\Translator;

/**
 * Translator.
 *
 * @category   Zend
 * @package    Zend_I18n
 * @subpackage Translator
 */
class Translator extends \Zend\I18n\Translator\Translator
{
    protected $scaffold;
    protected $scaffoldPath;
    protected $scaffoldFile;
    protected $scaffoldArray;

    public static function factory($options)
    {
        $translator = parent::factory($options);

        if(isset($options['scaffold']['enable']) 
            && $options['scaffold']['enable'] 
            && isset($options['scaffold']['path'])
            && $options['scaffold']['path']
        ){
            $translator->scaffoldPath = $path = $options['scaffold']['path'];
            $translator->scaffoldFile = $file = $translator->scaffoldPath . DIRECTORY_SEPARATOR . 'main.csv';

            if(!is_writable($path)){
                return $translator;
            }
            $translator->scaffold = true;
        }

        return $translator;
    }

    /**
     * Set the plugin manager for translation loaders
     *
     * @param  LoaderPluginManager $pluginManager
     * @return Translator
     */
    public function setPluginManager(LoaderPluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        return $this;
    }

    /**
     * Retrieve the plugin manager for translation loaders.
     *
     * Lazy loads an instance if none currently set.
     *
     * @return LoaderPluginManager
     */
    public function getPluginManager()
    {
        if (!$this->pluginManager instanceof LoaderPluginManager) {
            $this->setPluginManager(new LoaderPluginManager());
        }

        return $this->pluginManager;
    }



    protected function getScaffoldFile()
    {
        return $this->scaffoldFile;
    }


    public function __destruct()
    {
        $this->writeScaffoldFile();
    }

    public function scaffold($message)
    {
        $this->scaffoldArray[] = $message;
    }

    protected function writeScaffoldFile()
    {
        if(!$file = $this->scaffoldFile){
            return false;
        }

        if(!$messages = $this->scaffoldArray) {
            return true;
        }

        $messages = array_unique($messages);
        $fp = fopen($file, 'wb');
        foreach($messages as $message){
            fputcsv($fp, array($message, ''));
        }
        fclose($fp);
        return true;
    }

    /**
    * Translate a message.
    *
    * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @return string
     */
    public function translate($message, $textDomain = 'default', $locale = null)
    {
        if(true === $this->scaffold){
            $this->scaffold($message);
        }

        $trMessage = strtolower($message);
        $trdMessage = parent::translate($trMessage, $textDomain, $locale);
        if($trMessage == $trdMessage){
            return $message;
        }
        return $trdMessage;
    }
}
