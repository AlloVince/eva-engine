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
    
    protected $requestTime;
    
    protected $logData;
    
    protected $step;
    
    protected $serverSecret = "axcftksda!bn!";

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
        $this->amount = (float) $amount;
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

    public function getLogData()
    {
        return $this->logData;
    }

    public function setLogData($logData)
    {
        $this->logData = $logData;
        return $this;
    }

    public function getStep()
    {
        return $this->step;
    }

    public function setStep($step)
    {
        $this->step = $step;
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

    public function setRequestTime($requestTime)
    {
        $this->requestTime = (int) $requestTime;
        return $this;
    }

    public function getRequestTime()
    {
        if ($this->requestTime) {
            return $this->requestTime;
        }
    
        return $this->requestTime = (int) time();
    }

    public function getCallbackUrlParams()
    {
        return array(
            'adapter' => $this->getAdapterKey(),
            'amount' => $this->getAmount(),
            'service' => $this->getService(),
            'time' => $this->getRequestTime(),
        );
    }

    public function getSecretKey()
    {
        $params = $this->getCallbackUrlParams();
        $params['requestData'] = $this->getLogData();
        return md5(serialize($params));
    } 

    public function getSigned()
    {
        $params = $this->getCallbackUrlParams();
        $params['requestData'] = $this->getLogData();
        $params['step'] = $this->getStep();
        $params['secretKey'] = $this->getSecretKey();
        return md5(serialize($params) . $this->serverSecret);
    }

    public function saveRequestLog()
    {
        $itemModel = Api::_()->getModel('Payment\Model\Log');
        
        $user = Auth::getLoginUser();

        if (!$user) {
        }

        if(isset($user['isSuperAdmin'])){
        }

        $log = array(
            'amount' => $this->getAmount(),
            'logStep' => 'request',
            'service' => $this->getService(),
            'adapter' => $this->getAdapterKey(),
            'secretKey' => $this->getSecretKey(),
        );
        
        if ($this->getLogData()) {
            $log['requestData'] = serialize($this->logData);
        }

        if ($user) {
            $log['user_id'] = $user['id'];
        }
        
        $logId = $itemModel->setItem($log)->createLog(); 
    
        return $logId;
   }

   public function saveResponseLog($secretKey, $responseData = array())
    {
        $itemModel = Api::_()->getModel('Payment\Model\Log');
        
        $log = $itemModel->getLog($secretKey);

        if(!$log){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment log found'
            ));
            return;
        }
        
        if ($log['step'] == 'response' || $log['step'] == 'cancel') {
            return $log['id'];
        }

        $userId = $log['user_id'];

        $logData = array(
            'id' => $log['id'],
            'logStep' => $this->getStep(),
            'user_id' => $log['user_id'],
        );
        
        if ($responseData) {
            $logData['responseData'] = serialize($responseData);
        }
        
        $itemModel->setItem($logData)->saveLog();
        
        return $log['id'];
    }

   public function makeUrl($url, $step) 
   {
       if (!$url) {
           return $url;
       }
       
       $this->setStep($step);

       $params = $this->getCallbackUrlParams();
       $params['secretKey'] = $this->getSecretKey();
       $params['signed'] = $this->getsigned();

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
