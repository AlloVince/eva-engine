<?php
    
namespace Payment\Service\Adapter;

use Payment\Service\Exception;
use Eva\Api;
use Core\Auth;

abstract class AbstractAdapter
{
    protected $sandbox;
    
    protected $amount;
    
    protected $orderTitle;
    
    protected $orderId;
    
    protected $account;
    
    protected $callback;
    
    protected $notify;
    
    protected $cancel;
    
    protected $service;

    protected $consumerKey;

    protected $consumerSecret;

    protected $options;

    public function __construct(array $options = array())
    {
        if($options){
            $this->setOptions($options);
        }
    }
    
    public function getSandbox()
    {
        return $this->sandbox;
    }
    
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
        return $this; 
    }
    
    public function getService()
    {
        return $this->service;
    }
    
    public function setService($service)
    {
        $this->service = $service;
        return $this; 
    }

    public function getAmount()
    {
        return $this->amount;
    }
    
    public function setAmount($amount)
    {
        $this->amount = (int) $amount;
        return $this; 
    }

    public function getOrderTitle()
    {
        return $this->orderTitle;
    }

    public function setOrderTitle($orderTitle)
    {
        $this->orderTitle = $orderTitle;
        return $this; 
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this; 
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount($account)
    {
        $this->account = $account;
        return $this; 
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function getNotify()
    {
        return $this->notify;
    }

    public function setNotify($notify)
    {
        $this->notify = $notify;
        return $this;
    }

    public function getCancel()
    {
        return $this->cancel;
    }

    public function setCancel($cancel)
    {
        $this->cancel = $cancel;
        return $this;
    }

    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    public function setConsumerKey($consumerKey)
    {
        $this->consumerKey = $consumerKey;
        return $this;
    }

    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }

    public function setConsumerSecret($consumerSecret)
    {
        $this->consumerSecret = $consumerSecret;
        return $this;
    }

    public function getAdapterKey()
    {
        $className = get_class($this);
        $className = explode('\\', $className);
        return strtolower(array_pop($className));
    }

    public function getCallbackUrlParams()
    {
        return array(
            'adapter' => $this->getAdapterKey(),
            'amount' => $this->getAmount(),
            'service' => $this->getService(),
        );
    }

    public function getSecretKey()
    {
        $params = $this->getCallbackUrlParams();
        return md5(serialize($params));
    } 
    
    public function saveRequestLog()
    {
        $itemModel = Api::_()->getModel('Payment\Model\Log');
        
        $user = Auth::getLoginUser();

        if (!$user) {
        }

        if(isset($user['isSuperAdmin'])){
        }

        $logData = array(
            'amount' => $this->getAmount(),
            'logStep' => 'request',
            'service' => $this->getService(),
            'adapter' => $this->getAdapterKey(),
            'secretKey' => $this->getSecretKey(),
        );

        if ($user) {
            $logData['user_id'] = $user['id'];
        } 

        $logId = $itemModel->setItem($logData)->createLog(); 
    
        return $logId;
   }

   public function saveResponseLog($secretKey)
    {
        $itemModel = Api::_()->getModel('Payment\Model\Log');
        
        $log = $itemModel->getLog($secretKey);

        if(!$log){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment log found'
            ));
            return;
        }

        $logData = array(
            'id' => $log['id'],
            'logStep' => 'response',
        );
        
        $itemModel->setItem($logData)->saveLog();
    
        return $log['id'];
    }

    public function makeUrl($url) 
    {
        if (!$url) {
            return $url;
        }
        
        $params = $this->getCallbackUrlParams();
        $params['secretKey'] = $this->getSecretKey();

        return $url . "&" . http_build_query($params); 
    }

    public function setOptions(array $options = array())
    {
        if(!$options['account']){
            throw new Exception\InvalidArgumentException(sprintf('No account found in %s', get_class($this)));
        }

        $this->setSandbox($options['sandbox'])
            ->setOrderTitle($options['orderTitle'])
            ->setAccount($options['account']);

        if (isset($options['consumerKey'])) {
            $this->setConsumerKey($options['consumerKey']);
        }

        if (isset($options['consumerSecret'])) {
            $this->setConsumerSecret($options['consumerSecret']);
        }

        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }


}
