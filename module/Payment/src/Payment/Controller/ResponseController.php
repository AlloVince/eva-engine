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
        
        $responseData = $this->params()->fromQuery();
        if (!$responseData) {
            $responseData = $this->params()->fromPost();
        }

        if (isset($responseData['notify_id']) && isset($responseData['trade_status'])) {
            return $this->alipayResponse();
        }

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
                'No payment signed time found'
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
        $pay->setServiceLocator($this->getServiceLocator());
        $pay->setStep('response');
        $pay->saveResponseLog($secretKey, $responseData);

        if ($callback == 'notify') {
            return;
        }

        if($callback){
            return $this->redirect()->toUrl($callback);
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
        $pay->setServiceLocator($this->getServiceLocator());
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

    public function alipayResponse()
    {
        $callback = $this->params()->fromQuery('callback');
        $responseData = $this->params()->fromQuery();
        
        if (!isset($responseData['notify_id'])) {
            $responseData = $this->params()->fromPost();
            $method = 'notify';
        }

        $config = \Eva\Api::_()->getModuleConfig('Payment');
        $options = $config['payment']['alipay'];         
        $pay = new \Payment\Service\Payment('AlipayEc', false ,$options);
        $verify_result = $pay->verify();

        if ($verify_result) {
            $pay->setStep('response');
            $pay->saveResponseLog($responseData['out_trade_no'], $responseData); 
        }
    
        if ($callback == 'notify') {
            return;
        }

        if($callback){
            return $this->redirect()->toUrl($callback);
        }
    }
}
