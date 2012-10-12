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

        $config = $this->getServiceLocator()->get('config');
        $helper = $this->getEvent()->getApplication()->getServiceManager()->get('viewhelpermanager')->get('serverurl');

        $url = $helper() . $config['payment']['return_url_path'] . '?' . http_build_query(array(
            'callback' => urlencode($callback),
        ));
        
        $amount = (int) $amount;
        
        if (strtolower($adapter) == "paypalec") {
            $cancel = $helper() . $config['payment']['cancel_url_path'] . '?' . http_build_query(array(
                'callback' => urlencode($callback),
            ));
            $this->paypal($amount, $url, $cancel);
        } else {
            $this->alipay($amount, $url);
        }
    }

    public function paypal($amount, $callback, $cancel = null)
    {
        $config = \Eva\Api::_()->getModuleConfig('Payment');
        $options = $config['payment']['paypal']; 

        $pay = new \Payment\Service\Payment('PaypalEc', false, $options);

        return $pay->setAmount($amount)
            ->setCallback($callback)
            ->setCancel($cancel)
            ->sendRequest();
    }

    public function alipay($amount, $callback)
    {
        $config = \Eva\Api::_()->getModuleConfig('Payment');
        $options = $config['payment']['alipay']; 

        $orderId = time();
        $notify = $callback;

        $pay = new \Payment\Service\Payment('AlipayEc', false, $options);
        $link = $pay->setAmount($amount)
            ->setOrderId($orderId)
            ->setNotify($notify)
            ->setCallback($callback)
            ->sendRequest();

        return $this->redirect()->toUrl($link); 
    }
}
