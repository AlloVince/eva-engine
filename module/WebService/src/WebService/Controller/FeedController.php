<?php
namespace WebService\Controller;

use Zend\Mvc\Controller\AbstractActionController,
use Zend\View\Model\ViewModel;
use Eva\Api;
use WebService\WebServiceFactory;
use WebService\Exception;

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
        p($item);

        $webserice = WebServiceFactory::factory('Oauth2Douban', $item);
    }
}
