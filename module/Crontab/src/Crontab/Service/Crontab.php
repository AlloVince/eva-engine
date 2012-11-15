<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Crontab_Service
 * @author    AlloVince
 */

namespace Crontab\Service;

use Eva\Api;

/**
 * Base class for all protocols supporting tree
 *
 * @category   Core
 * @package    Core_Tree_Tree
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD Licens
 */
class Crontab
{
    protected $crontab;
    
    protected $crontabType = 'crontab';
    
    protected $filePath;

    public function setCrontabType($crontabType)
    {
        $this->crontabType = $crontabType;
        return $this;
    }

    public function getCrontabType()
    {
        return $this->crontabType;
    }

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this; 
    }
    
    public function getFilePath()
    {
        return $this->filePath; 
    }
    
    public function getCrontabList()
    {
        if ($this->crontabType == "crontab") {
            $crontab = $this->crontab;
            return $this->crontab->listJobs(); 
        } else {
            return file_get_contents($this->filePath);
        }
    }    

    public function cleanCrontab()
    {
        $crontab = $this->crontab;
        if ($this->crontabType == "crontab") { 
            $crontab->cleanManager();
            $crontab->save(false);
        } else {
            $crontab->disable($this->filePath);
            $crontab->save();
            file_put_contents($this->filePath, '');
        }
        return true;
    }

    public function saveCrontabList($content)
    {
        if (!$content) {
            return false;
        }

        $crontab = $this->crontab;

        if ($this->crontabType == "crontab") { 
            $lines = explode("\n", $content);

            foreach ($lines as $line) {
                $line = preg_replace('/(\#.*)/', '', $line);
                $job = $crontab->newJob($line);
                $crontab->add($job);
            }

            $crontab->save(false);
        } else {
            file_put_contents($this->filePath,$content);
            $crontab->enableOrUpdate($this->filePath);
            $crontab->save();
        }

        return true;
    } 

    public function __construct()
    {
        if (!$this->crontab) {
            $this->crontab = new \Crontab\Service\Vendor\CrontabManager();
        } 

        $config = Api::_()->getModuleConfig('Crontab');

        $this->setCrontabType($config['crontab']['type']);

        if ($this->crontabType == "file") {
            $path = realpath($config['crontab']['filePath']);

            if (!$path || !is_writeable($path)) {
                throw new \Crontab\Service\Exception\InvalidArgumentException(
                    sprintf(
                        '"%s" don\'t exists or isn\'t readable',
                        $path
                    )
                );
            } 
            
            $fullPath = $path . '/' . $config['crontab']['fileName'];
            if (!file_exists($fullPath)) {
                file_put_contents($fullPath, '');
            }

            $this->setFilePath($fullPath);
        }
    }
}
