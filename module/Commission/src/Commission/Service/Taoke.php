<?php
namespace Commission\Service;

use Eva\Api;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Json;

class Taoke
{
    protected $responseErrorCode;
    protected $responseErrorMessage;

    protected function getClient($url, $query, $method = 'POST')
	{
        $client = new Client();
		$client->setUri($url);
		if($method == 'POST') {
            $client->setMethod(Request::METHOD_POST);
			if($query){
                $client->setParameterPost($query);
			}
		} else {
            $client->setMethod(Request::METHOD_GET);
			if($query) {
                $client->setParameterGet($query);
			}
		}
		return $client;
    }

   protected function getSignature($query)
	{
		$options = $this->getOptions();
		ksort($query);
		$sign = $options['consumerSecret'];
		foreach($query as $key => $value){
			$sign .= $key . $value;
		}
		$sign .= $options['consumerSecret'];
		$sign = strtoupper(md5($sign));
		return $sign;
    }

	public function isResponseFailed($responseText, $responseCode = 200)
	{
		if (strpos($responseText, "error") !== false) {
			$errorResponse = $this->parseResponse($responseText);
			$this->responseErrorCode = $errorResponse['error_code'];
			$this->responseErrorMessage = $errorResponse['error_msg'];
			return true;
		}
		return false;
	}

    public function parseResponse($responseText, $dataType = 'json')
	{
		if($dataType == 'jsonp') {
			$lpos = strpos($responseText, "(");
			$rpos = strrpos($responseText, ")");
			$responseText = substr($responseText, $lpos + 1, $rpos - $lpos -1);
            return Json::decode($responseText, Json::TYPE_ARRAY);		
		}

		if($dataType == 'json') {
            return Json::decode($responseText, Json::TYPE_ARRAY);		
		}
	}

	public function getOptions()
    {
        $config = Api::_()->getConfig();
        return array(
            'consumerKey' => $config['taoke']['consumerKey'],
            'consumerSecret' => $config['taoke']['consumerSecret'],
            'nick' => $config['taoke']['nick'],
        );
	}

    public function getProduct($pid, $nick = null)
    {
		$options = $this->getOptions();
        $nick = $nick ? $nick : $options['nick'];

		$url = 'http://gw.api.taobao.com/router/rest';
		$query = array(
			'app_key' => $options['consumerKey'],
			'format' => 'json',
			'fields' => 'num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume',
			'method' => 'taobao.taobaoke.items.detail.get',
			'sign_method' => 'md5',
            'timestamp' => \Eva\Date\Date::getNow(),
			'v' => '2.0',
			'num_iids' => $pid,
			'nick' => $nick,
		);
		$query['sign'] = $this->getSignature($query);

		$client = $this->getClient($url, $query);
		$response = $client->send();
		$responseText = $response->getBody();
        if ($this->isResponseFailed($responseText) === true) {
            $responseErrorMessage = $this->getResponseErrorMessage();
            $responseErrorMessage = $responseErrorMessage ? $responseErrorMessage : 'response error';
        } else {
            $product =  $this->parseResponse($responseText);
            $product = $product['taobaoke_items_detail_get_response']['taobaoke_item_details']['taobaoke_item_detail'][0]['item'];

            $title = $product['title'];
            $query = array(
                'app_key' => $options['consumerKey'],
                'format' => 'json',
                'fields' => 'num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume',
                'method' => 'taobao.taobaoke.items.get',
                'sign_method' => 'md5',
                'timestamp' => \Eva\Date\Date::getNow(),
                'v' => '2.0',
                'keyword' => $title,
            );
            $query['sign'] = $this->getSignature($query);

            $client = $this->getClient($url, $query);
            $response = $client->send();
            $responseText = $response->getBody();
            if ($this->isResponseFailed($responseText) === true) {
                $responseErrorMessage = $this->responseErrorMessage;
                $responseErrorMessage = $responseErrorMessage ? $responseErrorMessage : 'response error';
            } else {

                $products = $this->parseResponse($responseText);
                $productsCount =  $products['taobaoke_items_get_response']['total_results'];
                $products = $products['taobaoke_items_get_response']['taobaoke_items']['taobaoke_item'];

                $res = array();
                foreach($products as $commissionProduct){
                    if($product['num_iid'] == $commissionProduct['num_iid']){
                        $res = $commissionProduct;
                    }
                }

                return $res;
            }
        }

        return array();
    }
}
