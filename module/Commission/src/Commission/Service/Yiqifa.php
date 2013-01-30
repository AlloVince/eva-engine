<?php
namespace Commission\Service;

use Eva\Api,
    Core\Auth,
    Eva\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Json;

class Yiqifa
{
	public function getThirdpartAssociateUrl($identifier, $userTag = null, $associateCodeArray = array())
	{
        $config = Api::_()->getConfig();
		$storeKey = $this->_getStorekey();
		$thirdpartParams = $config['system']['3rd']['yiqifa']['store'][$storeKey];
		$thirdpartParams = $thirdpartParams ? $thirdpartParams : $this->_thirdpartParams;

		if(!$thirdpartParams){
			return $this->getStoreSelfAssociateUrl($identifier);
		}

		parse_str($thirdpartParams, $params);
		if(!$params){
			return $this->getStoreSelfAssociateUrl($identifier);
		}
		$params['e'] = $userTag ? $userTag : $config['system']['sjl']['redirect']['source'];
		$params['t'] = $this->getProductUrl($identifier);
		//yiqifa not accept url encoded Link
		$url = 'http://p.yiqifa.com/c?' . htmlspecialchars(urldecode(http_build_query($params)));
		return $url;
	}
}
