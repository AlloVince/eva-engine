<?php

namespace Core\Admin\Controller;

use Eva\Mvc\Controller\RestfulModuleController;
use Eva\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class QueueController extends RestfulModuleController
{
    protected $addResources = array(
        'monitor',
        'add',
        'job',
        'run',
        'clear',
        'view',
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

    public function restGetQueueView()
    {

    }

    public function restGetQueueAdd()
    {
        $this->changeViewModel('json');
        $queue = $this->params()->fromQuery('queue', 'default');
        $jobId = \Resque::enqueue($queue, 'Core\Jobs\TestJob', array('name' => $queue), true);
        return new JsonModel(array(
            'id' => $jobId
        ));
    }

    public function restGetQueueClear()
    {
        $this->changeViewModel('json');
        $queue = $this->params()->fromQuery('queue', 'default');
        $res = \Resque_Stat::clear($queue);
        return new JsonModel(array(
            'res' => $res
        ));
    }

    public function restGetQueueRun()
    {
        $this->changeViewModel('json');
        $worker = $this->params()->fromQuery('worker', 'all');
        $path = realpath(EVA_ROOT_PATH . '/workers/worker');
        $command = "QUEUE=* php $path &";
        $out = popen("QUEUE=* php $path &", "r"); 
        pclose($out);
        return new JsonModel(array(
            'command' => $command
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
            $queue = $this->params()->fromQuery('queue');
            $command = 'ps ux';
            if($queue){
                $command .= ' | grep "php ' . realpath(EVA_ROOT_PATH . '/workers') . '/worker" | grep -v grep';
            } else {
                $command .= ' | grep "php ' . realpath(EVA_ROOT_PATH . '/workers') . '" | grep -v grep';
            }
            exec($command, $pslist);
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
                'command' => $command,
                'items' => $psData,
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
