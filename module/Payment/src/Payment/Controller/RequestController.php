<?php
namespace Payment\Controller;

use Eva\Mvc\Controller\ActionController,
    Payment\Service\Exception,
    Eva\View\Model\ViewModel;

class RequestController extends ActionController
{
    protected $addResources = array(
    );

    public function indexAction()
    {
        $adapter = $this->params()->fromQuery('adapter');
        $callback = $this->params()->fromQuery('callback');
        $amount = $this->params()->fromQuery('amount');
        $data = $this->params()->fromQuery('data');
        $signed = $this->params()->fromQuery('signed');
        
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
                'No oauth callback found'
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

        $config = $this->getServiceLocator()->get('config');
        $helper = $this->getEvent()->getApplication()->getServiceManager()->get('viewhelpermanager')->get('serverurl');

        $url = $helper() . $config['payment']['return_url_path'] . '?' . http_build_query(array(
            'callback' => $callback,
        ));

        $amount = (float) $amount;

        if (strtolower($adapter) == "paypalec") {
            $cancel = $helper() . $config['payment']['cancel_url_path'] . '?' . http_build_query(array(
                'callback' =>$callback,
            ));
            $this->paypal($amount, $url, $data, $cancel);
        } else {
            $this->alipay($amount, $url, $data);
        }
    }

    public function paypal($amount, $callback, $data = array(), $cancel = null)
    {
        $config = \Eva\Api::_()->getModuleConfig('Payment');
        $options = $config['payment']['paypal']; 

        $pay = new \Payment\Service\Payment('PaypalEc', false, $options);
        $pay->setServiceLocator($this->getServiceLocator());

        if (isset($data['title'])) {
            $pay->setOrderTitle($data['title']);
        }

        return $pay->setAmount($amount)
            ->setCallback($callback)
            ->setCancel($cancel)
            ->setLogData($data)
            ->sendRequest();
    }

    public function alipay($amount, $callback, $data = array())
    {
        $config = \Eva\Api::_()->getModuleConfig('Payment');
        $options = $config['payment']['alipay']; 

        $orderId = time();
        $notify = $callback;

        $pay = new \Payment\Service\Payment('AlipayEc', false, $options);
        $pay->setServiceLocator($this->getServiceLocator());
        if (isset($data['title'])) {
            $pay->setOrderTitle($data['title']);
        }
        $link = $pay->setAmount($amount)
            ->setOrderId($orderId)
            ->setNotify($notify)
            ->setCallback($callback)
            ->setLogData($data)
            ->sendRequest();

        return $this->redirect()->toUrl($link); 
    }

    public function authenticate($params)
    {
        $adapter     = $params['adapter'];
        $callback    = $params['callback'];
        $amount      = $params['amount'];
        $data        = $params['data'];
        $signed      = $params['signed'];

        $config = $this->getServiceLocator()->get('config');
        
        $queryString = http_build_query(array(
            'adapter'  => $adapter,
            'amount'   => $amount,
            'callback' => $callback,
            'data'     => $data,
        ));
        $paymentSecretKey = $config['payment']['paymentSecretKey'];

        if(!$paymentSecretKey){
            throw new Exception\InvalidArgumentException(sprintf(
                'Payment config error'
            ));
        }

        $authenticate = md5($queryString . $paymentSecretKey);
        
        if ($authenticate !== $signed) {
            return false;    
        }    
    
        return true;
    }
}
