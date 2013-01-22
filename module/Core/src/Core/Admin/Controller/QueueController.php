<?php

namespace Core\Admin\Controller;

use Eva\Mvc\Controller\RestfulModuleController;
use Eva\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class QueueController extends RestfulModuleController
{
    protected $addResources = array(
        'monitor',
        'job',
        'run',
        'clear',
        'kill',
    );

    public function restIndexQueue()
    {
        $queues = \Resque::queues();
        foreach($queues as $key => $queue){
            $queues[$key] = array(
                'name' => $queue,
                'size' => \Resque::size($queue),
            );
        }

        return array(
            'items' => $queues,
        );
    }

    public function restGetQueueRun()
    {
        $this->changeViewModel('json');
        $worker = $this->params()->fromQuery('worker', 'all');
        $path = realpath(EVA_ROOT_PATH . '/workers/worker');
        $command = "QUEUE=* php $path &";
        p($command);
        exit;
        return new JsonModel(array(
            //'res' => exec("QUEUE=* php $path &")
            'res' => $command
        ));
    }

    public function restGetQueueKill()
    {
        $pid = $this->params('id');
        $this->changeViewModel('json');
        return new JsonModel(array(
            'res' => exec("kill $pid")
        ));
    }

    public function restGetQueueMonitor()
    {
        if($this->params()->fromQuery('format') == 'json'){
            $this->changeViewModel('json');
            $pslist = array();
            $psData = array();
            exec("ps -ux", $pslist);
            $psCount = count($pslist);
            if($psCount <= 0){
                return new JsonModel(array(
                ));
            }

            $column = array(
                'USER',
                'PID',
                'CPU',
                'MEM',
                'VSZ',
                'RSS',
                'TTY',
                'STAT',
                'START',
                'TIME',
                'COMMAND',
            );

            for($i = 0; $i < $psCount; $i++) {
                $item = array();
                $process = $pslist[$i];
                if($i === 0 && 0 === strpos($process, 'USER')){
                    continue;
                }
                $item[0] = strtok($process, " "); 
                for($s = 1; $s < 11; $s++) { 
                    $item[$s]  = strtok(" "); 
                } 
                $psData[] = @array_combine($column, $item);
            } 
            $psData = \Eva\Stdlib\Arraylib\Sort::multiSortArray($psData, 'CPU', 'SORT_DESC');
            return new JsonModel(array(
                'items' => $psData
            ));
        }
        return array();
    }

    public function restGetQueueJob()
    {
        $jobId = $this->params()->fromQuery('job');
        $status = new \Resque_Job_Status($jobId);
        if(!$status->isTracking()) {
            return array(
                'status' => -1,
            );
        }

        return array(
            'status' => $status->get()
        );
    }
}
