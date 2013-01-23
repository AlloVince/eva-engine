<?php

namespace Core\Admin\Controller;

use Eva\Mvc\Controller\RestfulModuleController;
use Eva\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Eva\Api;

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
        $config = Api::_()->getConfig();
        $defaultQueues = $config['queue']['default_queues'];
        $queues = array_merge($defaultQueues, \Resque::queues());
        $queues = array_unique($queues);
        foreach($queues as $key => $queue){
            $queues[$key] = array(
                'name' => $queue,
                'size' => \Resque::size($queue),
            );
        }

        $pslist = $this->getSystemProcessList('ps ux | grep "php ' . realpath(EVA_ROOT_PATH . '/workers') . '" | grep -v grep');
        foreach($queues as $key => $queue){
            $workerCount = 0;
            foreach($pslist as $psKey => $ps){
                $offset = strlen($ps['COMMAND']) - strlen($queue['name']);
                if($offset === strrpos($ps['COMMAND'], $queue['name'])){
                    $workerCount++;
                    unset($pslist[$psKey]);
                }
            }
            $queues[$key]['workers'] = $workerCount;
        }
        $publicWorkerCount = count($pslist);

        $return = array(
            'items' => $queues,
            'publicWorkerCount' => $publicWorkerCount,
        );


        if($this->params()->fromQuery('format') == 'json'){
            $this->changeViewModel('json');
            return new JsonModel($return);
        }

        return $return;
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
            'id' => $jobId,
            'size' => \Resque::size($queue), 
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
        $queue = $this->params()->fromQuery('queue');
        $this->changeViewModel('json');
        $worker = $this->params()->fromQuery('worker', 'all');
        $path = realpath(EVA_ROOT_PATH . '/workers/worker');
        if($queue){
            $command = "QUEUE=$queue php $path $queue &";
        } else {
            $command = "QUEUE=* php $path &";
        }
        $out = popen($command, "r"); 
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
            $queue = $this->params('id');
            $command = 'ps ux';
            if($queue != 'monitor'){
                $command .= ' | grep "php ' . realpath(EVA_ROOT_PATH . '/workers/worker') . ' ' . $queue . '" | grep -v grep';
            } else {
                $command .= ' | grep "php ' . realpath(EVA_ROOT_PATH . '/workers/worker') . '" | grep -v grep';
            }
            $pslist = $this->getSystemProcessList($command);
            return new JsonModel(array(
                'command' => $command,
                'items' => $pslist,
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

    protected function getQueueList()
    {
    
    }

    protected function getSystemProcessList($command)
    {
        $pslist = array();

        exec($command, $pslist);
        $psCount = count($pslist);
        if($psCount <= 0){
            return array();
        }

        $psData = array();
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
            $token = ' ';
            for($s = 0; $s < 10; $s++) {
                $tokenIndex = strpos($process, $token);
                $text = substr($process, 0, $tokenIndex);
                $process = ltrim(substr($process, $tokenIndex));
                $item[$s] = $text;
            } 
            $item[10] = rtrim(substr($process, $tokenIndex));
            $psData[] = array_combine($column, $item);
        } 
        $psData = \Eva\Stdlib\Arraylib\Sort::multiSortArray($psData, 'CPU', 'SORT_DESC');
        return $psData;
    }
}
