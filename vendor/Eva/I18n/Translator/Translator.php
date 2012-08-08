<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Eva\I18n\Translator;

use Zend\I18n\Translator\TextDomain;

/**
 * Translator.
 *
 * @category   Eva
 * @package    Eva_I18n
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
        sort($messages);
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

    protected function mergeMessages($textDomain, $locale, $messages)
    {
        if(!isset($this->messages[$textDomain][$locale])){
            return $this->messages[$textDomain][$locale] = $messages;
        }

        $loadedMessages = $this->messages[$textDomain][$locale];
        return $this->messages[$textDomain][$locale] = new TextDomain(array_merge((array) $loadedMessages, (array) $messages));
    }

    /**
     * Load messages for a given language and domain.
     *
     * @param  string $textDomain
     * @param  string $locale
     * @return void
     */
    protected function loadMessages($textDomain, $locale)
    {
        if (!isset($this->messages[$textDomain])) {
            $this->messages[$textDomain] = array();
        }

        if (null !== ($cache = $this->getCache())) {
            $cacheId = 'Zend_I18n_Translator_Messages_' . md5($textDomain . $locale);

            if (false !== ($result = $cache->getItem($cacheId))) {
                $this->messages[$textDomain][$locale] = $result;
                return;
            }
        }

        // Try to load from pattern
        if (isset($this->patterns[$textDomain])) {
            foreach ($this->patterns[$textDomain] as $pattern) {
                $filename = $pattern['baseDir']
                          . '/' . sprintf($pattern['pattern'], $locale);
                if (is_file($filename)) {
                    $messages = $this->getPluginManager()
                         ->get($pattern['type'])
                         ->load($filename, $locale);
                    //EvaEngine : add merge array
                    $this->mergeMessages($textDomain, $locale, $messages);
                }
            }
        }

        // Load concrete files, may override those loaded from patterns
        foreach (array($locale, '*') as $currentLocale) {
            if (!isset($this->files[$textDomain][$currentLocale])) {
                continue;
            }

            $file = $this->files[$textDomain][$currentLocale];
            $messages = $this->getPluginManager()
                 ->get($file['type'])
                 ->load($file['filename'], $locale);
            //EvaEngine : add merge array
            $this->mergeMessages($textDomain, $locale, $messages);

            unset($this->files[$textDomain][$currentLocale]);
        }

        // Cache the loaded text domain
        if ($cache !== null) {
            $cache->setItem($cacheId, $this->messages[$textDomain][$locale]);
        }
    }
}
