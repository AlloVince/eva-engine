<?php
namespace Webservice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Api;
use Webservice\WebserviceFactory;
use Webservice\Exception;

class FeedController extends AbstractActionController
{
    public function indexAction()
    {
        $this->changeViewModel('json');
        $itemModel = Api::_()->getModel('Oauth\Model\Accesstoken');
        $dataClass = $itemModel->getItem()->getDataClass();
        $item = $dataClass->where(function($where){
            $where->equalTo('adapterKey', 'douban');
            $where->equalTo('tokenStatus', 'active');
            $where->equalTo('version', 'Oauth2');
            //$where->greaterThan('expireTime', 0);
            return $where;
        })
        ->order('expireTime ASC')
        ->find('one');
        $item = (array) $item;

        $webserice = WebserviceFactory::factory('Oauth2Douban', $item, $this->getServiceLocator());
        $adapter = $webserice->getAdapter();
        $adapter->setApiUri('https://api.douban.com/v2/book/20389191');
        //https://api.douban.com/v2/user/~me
        $data = $adapter->getApiData();
        p($adapter->isApiResponseSuccess());
        p($data);
        p($adapter->getMessages());
    }
}
