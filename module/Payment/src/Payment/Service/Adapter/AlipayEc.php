<?php
    
namespace Payment\Service\Adapter;

use Payment\Service\Adapter\AbstractAdapter;
use Payment\Service\Exception;

class AlipayEc extends AbstractAdapter
{

    protected $service = 'alipay';

    public function sendRequest()
    {
        if(!$this->getAmount()){
            throw new Exception\InvalidArgumentException(sprintf('No amount found in %s', get_class($this)));
        }
        
        if(!$this->options['accountType']){
            throw new Exception\InvalidArgumentException(sprintf('No accountType found in %s', get_class($this)));
        }
        
        if(!$this->getConsumerKey()){
            throw new Exception\InvalidArgumentException(sprintf('No ConsumerKey found in %s', get_class($this)));
        }

        if(!$this->getConsumerSecret()){
            throw new Exception\InvalidArgumentException(sprintf('No ConsumerSecret found in %s', get_class($this)));
        }

        if(!$this->getOrderId()){
            throw new Exception\InvalidArgumentException(sprintf('No Order Id found in %s', get_class($this)));
        }
        
        $logId = $this->saveRequestLog();
        $returnUrl = $this->makeUrl($this->getCallback(), 'response'); 
        $notifyUrl = $this->makeUrl($this->getNotify(), 'response'); 
        
        $parameter = array(
			"service" => $this->options['accountType'],
			"partner" => $this->getConsumerKey(),            
			"return_url" => $returnUrl, 
		    "notify_url" => $notifyUrl, 
			"_input_charset" => 'utf-8',
			"subject" => $this->getOrderTitle(),                                         
			"body" => $this->getOrderTitle(),                                         
			"out_trade_no" => $this->getOrderId(),                      
			"logistics_fee"=>'0.00',             
			"logistics_payment"=>'BUYER_PAY',             
			"logistics_type"=>'EXPRESS',    		
			"price" => $this->getSandbox() ? 0.1 : $this->getAmount(),                         
			"payment_type"=>"1",               
			"quantity" => "1",                 
			"seller_email" => $this->getAccount()             
		);
        
        include (EVA_ROOT_PATH . '/module/Payment/src/Payment/Service/Vendor/alipay_service.php');
        
        $alipay = new \Payment\Service\Vendor\alipay_service();
        $alipay->alipay_service($parameter,$this->getConsumerSecret(),"MD5");

        return $link = $alipay->create_url(); 
    }
}
