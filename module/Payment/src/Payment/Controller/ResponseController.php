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
        $requestTime = $this->params()->fromQuery('time');
        $signed = $this->params()->fromQuery('signed');
        $responseData = $this->params()->fromPost();
        
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
        
        if(!$requestTime){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment request time found'
            ));
        }
        
        if(!$signed){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment request time found'
            ));
        }
        
        if (!$this->authenticate($this->params()->fromQuery())) {
           throw new Exception\InvalidArgumentException(sprintf(
                'Signed not match'
            )); 
            return; 
        }
        
        $adapter = $adapter == 'paypalec' ? 'PaypalEc' : 'AlipayEc';
        $pay = new \Payment\Service\Payment($adapter);
        $pay->setStep('response');
        $pay->saveResponseLog($secretKey, $responseData);
        
        if($callback){
            $this->redirect()->toUrl($callback);
        }
    }

    public function authenticate($params)
    {
        $adapter     = $params['adapter'];
        $callback    = $params['callback'];
        $amount      = $params['amount'];
        $secretKey   = $params['secretKey'];
        $requestTime = $params['time'];
        $signed      = $params['signed'];

        $itemModel = \Eva\Api::_()->getModel('Payment\Model\Log');
        $log = $itemModel->getLog($secretKey, array(
            'self' => array(
                '*',
                'unserializeRequestData()',
                'unserializeResponseData()',
            ),
        ));
        
        if (!$log) {
            return false;
        }

        $adapter = $adapter == 'paypalec' ? 'PaypalEc' : 'AlipayEc';

        $pay = new \Payment\Service\Payment($adapter);
        $authenticate = $pay->setAmount($amount)
            ->setRequestTime($requestTime)
            ->setlogData($log['requestData'])
            ->setStep('response')
            ->getSigned();

        if ($authenticate !== $signed) {
            return false;    
        }    
    
        return true;
    }
}
