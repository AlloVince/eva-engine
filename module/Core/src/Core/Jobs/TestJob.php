<?php

namespace Core\Jobs;

use Zend\Log;

class TestJob
{
    public function perform()
    {
        $queueName = isset($this->args['name']) ? $this->args['name'] : 'error';
        $writer = new Log\Writer\Stream(EVA_PUBLIC_PATH . '/logs/' . $queueName . '.log');
        $logger = new Log\Logger();
        $logger->addWriter($writer);
        $logger->info("Worker $queueName is working");
    }
}
