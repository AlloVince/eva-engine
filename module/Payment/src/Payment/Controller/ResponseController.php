<?php
namespace Payment\Controller;

use Eva\Mvc\Controller\ActionController,
    Payment\Service\Exception,
    Eva\View\Model\ViewModel;

class ResponseController extends ActionController
{
    protected $addResources = array(
    );

    public function indexAction()
    {
        $adapter = $this->params()->fromQuery('adapter');
        $callback = $this->params()->fromQuery('callback');
        $amount = $this->params()->fromQuery('amount');
        $secretKey = $this->params()->fromQuery('secretKey');

        if(!$amount){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment amount found'
            ));
        }
        
        if(!$adapter){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment adapter key found'
            ));
        }

        if(!$callback){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment callback found'
            ));
        }
        
        if(!$secretKey){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment secretKey found'
            ));
        }

        $callback = urldecode($callback);
        
        $adapter = $adapter == 'paypalec' ? 'PaypalEc' : 'AlipayEc';

        $pay = new \Payment\Service\Payment($adapter);
        $authenticate = $pay->setAmount($amount)->getSecretKey();
        
        if ($authenticate != $secretKey) {
            throw new Exception\InvalidArgumentException(sprintf(
                'SecretKey not match'
            )); 
            return;
        }
    
        $pay->saveResponseLog($secretKey);

        if($callback){
            $this->redirect()->toUrl($callback);
        }
    }
}
