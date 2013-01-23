<?php

namespace Core\Jobs;

use Eva\Api;

class SendNewsletter
{
    public function perform()
    {
        $writer = new \Zend\Log\Writer\Stream(EVA_PUBLIC_PATH . '/worker.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('Informational message');
    }
}
