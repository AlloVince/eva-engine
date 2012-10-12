<?php
    
namespace Payment\Service\Adapter;

use Payment\Service\Adapter\AbstractAdapter;
use Payment\Service\Exception;

class PaypalEc extends AbstractAdapter
{
    protected $requestUrl = "https://www.paypal.com/cgi-bin/webscr";

    protected $sandboxUrl = "https://www.sandbox.paypal.com/cgi-bin/webscr";

    protected $service = 'paypal';

    public function sendRequest()
    {
        if(!$this->getAmount()){
            throw new Exception\InvalidArgumentException(sprintf('No amount found in %s', get_class($this)));
        }
        
        include (EVA_ROOT_PATH . '/module/Payment/src/Payment/Service/Vendor/paypal.class.php');
        
        $paypal = new \Payment\Service\Vendor\paypal_class();
        $paypal->paypal_class();
        $paypal->paypal_url = $this->requestUrl;

        if ($this->getSandbox()) {
            $paypal->paypal_url = $this->sandboxUrl;
		}

        $logId = $this->saveRequestLog();
        
        $url = $this->makeUrl($this->getCallback());
        $notifyUrl = $url;
        $cancelUrl = $this->makeUrl($this->getCancel());

        $paypal->add_field('charset', 'utf-8');
		$paypal->add_field('business', $this->getAccount());
		$paypal->add_field('return', $url);
		$paypal->add_field('cancel_return', $cancelUrl);
		$paypal->add_field('notify_url', $notifyUrl);
		$paypal->add_field('item_name', $this->getOrderTitle());
		$paypal->add_field('item_number', time());
		$paypal->add_field('currency_code', $this->options['currency']);
		$paypal->add_field('no_shipping', 1);
		$paypal->add_field('amount', $this->getAmount());

        $paypal->submit_paypal_post();
    }
}
