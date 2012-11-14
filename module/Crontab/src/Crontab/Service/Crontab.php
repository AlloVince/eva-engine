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
    
    public function getCrontabList()
    {
        $crontab = $this->crontab;
        return $this->crontab->listJobs();
    }    
    
    public function cleanCrontab()
    {
        $crontab = $this->crontab;
        $crontab->cleanManager();
        $crontab->save(false);
        return true;
    }
    
    public function saveCrontabList($content)
    {
        if (!$content) {
            return false;
        }

        $lines = explode("\n", $content);

        $crontab = $this->crontab;
        foreach ($lines as $line) {
            $line = preg_replace('/(\#.*)/', '', $line);
            $job = $crontab->newJob($line);
            $crontab->add($job);
        }
        
        $crontab->save(false);
    
        return true;
    } 

    public function __construct()
    {
        if (!$this->crontab) {
            $this->crontab = new \Crontab\Service\Vendor\CrontabManager();
        } 
    }

}
