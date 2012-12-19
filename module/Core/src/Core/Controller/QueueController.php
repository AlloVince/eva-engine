<?php

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

class QueueController extends AbstractActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        $queueName = $request->getParam('queueName');

        return $queueName;
    }
}
