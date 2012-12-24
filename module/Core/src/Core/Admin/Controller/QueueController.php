<?php

namespace Core\Admin\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class QueueController extends RestfulModuleController
{
    protected $addResources = array(
    );

    public function indexAction()
    {
        $request = $this->getRequest();
        $queue = new \Core\Queue('Mongodb', array(
            'adapterNamespace' => 'Core\Queue\Adapter',
        ));
        $queueList = $queue->getQueues();

        foreach($queueList as $key => $item){
            $queueList[$key] = array(
                'name' => $item,
                'debug' => $queue->debugInfo()
            );
        }
        return array(
            'items' => $queueList,
        );
    }

    public function restGetQueue()
    {
        $id = $this->params('id');
        $queue = new \Core\Queue('Mongodb', array(
            'adapterNamespace' => 'Core\Queue\Adapter',
            'name' => $id,
        ));
        $item = $queue->debugInfo();
        $item['count'] = $queue->count();
        return array(
            'item' => $item,
        );
    }
}
