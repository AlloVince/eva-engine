<?php
namespace Webservice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Eva\Api;
use Webservice\WebserviceFactory;
use Webservice\Exception;

class UserController extends AbstractActionController
{
    public function indexAction()
    {
        $serviceKey = $this->params()->fromQuery('service');
        $serviceType = $this->params()->fromQuery('type');
        $userId = $this->params()->fromQuery('uid');

        $serviceKey = ucfirst(strtolower($serviceKey));
        $serviceType = ucfirst(strtolower($serviceType));


        $this->changeViewModel('json');
        $itemModel = Api::_()->getModel('Oauth\Model\Accesstoken');
        $dataClass = $itemModel->getItem()->getDataClass();
        $item = $dataClass->where(function($where) use ($serviceKey, $serviceType, $userId){
            $where->equalTo('adapterKey', strtolower($serviceKey));
            $where->equalTo('tokenStatus', 'active');
            $where->equalTo('version', $serviceType);
            $where->equalTo('user_id', $userId);
            return $where;
        })
        ->find('one');
        $item = (array) $item;

        if(!$item){
            return new JsonModel();
        }

        $webserice = WebserviceFactory::factory($serviceType . $serviceKey, $item, $this->getServiceLocator());
        $adapter = $webserice->getAdapter();

        $userApi = $adapter->uniformApi('User');
        $userId = $item['remoteUserId'];
        $user = $userApi->setUserId($userId)->getData();
        //$user2 = $userApi->setUserId($userId)->getUser();


        $json = $userApi->getLastRawResponse();
        //p($userApi->getAdapter()->getClient()->getRequest()->toString());
        //p($userApi->getAdapter()->getClient()->getResponse()->getBody());
        //$me = $userApi->getMe();
        //$profile = $userApi->setUserId($userId)->getProfile();

        /*
        $feedApi = $adapter->uniformApi('Feed');
        $feedApi->createFeed(array(
            'content' => 'abc',
        ));
        */
        //$data = $adapter->uniformApi('User')->fromUser()->getUserName();
        //p($data);
        /*
        $data = $adapter->api('https://api.douban.com/v2/user', null, 'GET', array(
            'q' => 'a',
            'start' => '0',
        ));
        */

        /*
        $adapter->setApiUri('https://api.douban.com/v2/book/20389191');
        //https://api.douban.com/v2/user/~me
        $data = $adapter->getApiData();
        p($adapter->isApiResponseSuccess());
        p($data);
        p($adapter->getMessages());
        */
    }
}
