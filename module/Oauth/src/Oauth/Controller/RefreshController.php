<?php
namespace Oauth\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\JsonModel,
    Oauth\OauthService,
    Oauth\Exception,
    Eva\Api;

class RefreshController extends AbstractActionController
{
    public function expireAction()
    {
        $this->changeViewModel('json');
        $itemModel = Api::_()->getModel('Oauth\Model\Accesstoken');
        $dataClass = $itemModel->getItem()->getDataClass();
        $dataClass->where(function($where){
            $where->equalTo('tokenStatus', 'active');
            $where->equalTo('version', 'Oauth2');
            $where->lessThan('expireTime', \Eva\Date\Date::getNow());
            return $where;
        })->save(array(
            'tokenStatus' => 'expried',
        ));
        return new JsonModel();
    }

    public function tokenAction()
    {
        $this->changeViewModel('json');
        $itemModel = Api::_()->getModel('Oauth\Model\Accesstoken');
        $dataClass = $itemModel->getItem()->getDataClass();
        $item = $dataClass->where(function($where){
            $where->equalTo('tokenStatus', 'active');
            $where->equalTo('version', 'Oauth2');
            return $where;
        })
        ->order('expireTime DESC')
        ->find('one');

        $item = (array) $item;
        $oauth = new OauthService();
        $oauth->initByAccessToken($item);
        $adapter = $oauth->getAdapter();
        $client = $adapter->getHttpClient();

        /*
        $client->setUri('https://api.weibo.com/2/users/show.json');
        $client->setParameterGet(array(
            'screen_name' => 'Allo'
        ));
        $client->setUri('https://api.douban.com/v2/user/~me');
        $response = $client->send();
        */
        $adapter->refreshAccessToken();
    }
}
