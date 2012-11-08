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
            'callback' => $callback,
        ));
        
        $amount = (float) $amount;
        
        $data = array(
            'test' => 'haha',
            'test11' => 'haha',
        );

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
        $link = $pay->setAmount($amount)
            ->setOrderId($orderId)
            ->setNotify($notify)
            ->setCallback($callback)
            ->setLogData($data)
            ->sendRequest();

        return $this->redirect()->toUrl($link); 
    }
}
