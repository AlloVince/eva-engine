<?php

namespace Core\Admin\Controller;

use Eva\Mvc\Controller\RestfulModuleController;
use Eva\View\Model\ViewModel;

class QueueController extends RestfulModuleController
{
   protected $addResources = array(
      'job'
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
