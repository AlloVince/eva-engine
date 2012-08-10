<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\Api,
    Eva\View\Model\ViewModel;

class CsvController extends RestfulModuleController
{
    protected $addResources = array(
    );

    protected function loadCsv($filename)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Could not open file %s for reading',
                $filename
            ));
        }

        $messages = array();
        if (($handle = fopen($filename, "r")) !== false) {
            while (($cell = fgetcsv($handle)) !== false) {
                if(!isset($cell[0])){
                    continue;
                }
                $messages[$cell[0]] = isset($cell[1]) ?  $cell[1] : '';
            }
            fclose($handle);
        }

        return $messages;
    }

    protected function getTranlatedMessages()
    {
        $translator = $this->event->getApplication()->getServiceManager()->get('Translator');
        //tranlate anything to load files
        $translator->translate('init');

        $messages = $translator->getMessages();
        return $messages['default'][$translator->getLocale()];
    }

    protected function writeToFile($file, $messages)
    {
        $fp = fopen($file, 'wb');
        //Create UTF-8 file
        fwrite($fp,pack("CCC",0xef,0xbb,0xbf));
        foreach($messages as $key => $message){
            fputcsv($fp, array($key, ''));
        }
        fclose($fp);
        return true;
    }


    public function restIndexCsv()
    {
        $config = Api::_()->getConfig();
        if(!$config['translator']['scaffold']['path']){
            return;
        }


        $files = glob($config['translator']['scaffold']['path'] . '/*.csv');
        $messages = array();
        foreach($files as $file){
            $messages = array_merge($messages, $this->loadCsv($file));
        }
        ksort($messages);

        $translatedMessages = (array) $this->getTranlatedMessages();
        $csvArray = array();
        foreach($messages as $key => $message){
            $findKey = strtolower($key);
            if(isset($translatedMessages[$findKey])){
                unset($messages[$key]);
            }
        }
        unset($translatedMessages);
        $file = $config['translator']['scaffold']['path'] . '/_tmp.csv';
        $this->writeToFile($file, $messages);

        return array('csv' => file_get_contents($file));
    }
}
