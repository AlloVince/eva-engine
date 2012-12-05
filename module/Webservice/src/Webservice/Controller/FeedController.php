<?php
namespace Webservice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Eva\Api;
use Webservice\WebserviceFactory;
use Webservice\Exception;

class FeedController extends AbstractActionController
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

        $content = 'Hello World';
        $feedApi = $adapter->uniformApi('Feed');
        $feedApi->setUserId($item['remoteUserId']);
        $feed = $feedApi->createFeed(array(
            'content' => $content,
        ));

        $json = $feedApi->getLastRawResponse();
        p($feedApi->getAdapter()->getClient()->getRequest()->toString());
        p($feedApi->getAdapter()->getClient()->getResponse()->getBody());


        exit;
        return new JsonModel(array(
            'data' => $feed
        ));
    }

    public function syncAction()
    {
        $serviceKey = $this->params()->fromQuery('service');
        $serviceType = $this->params()->fromQuery('type');
        $content = $this->params()->fromQuery('content');
        $user = \Core\Auth::getLoginUser();
        $userId = $user['id'];

        $serviceKey = ucfirst(strtolower($serviceKey));
        $serviceType = ucfirst(strtolower($serviceType));

        $this->changeViewModel('json');
        if(!$userId || !$content){
            return new JsonModel();
        }

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

        $feedApi = $adapter->uniformApi('Feed');
        $feedApi->setUserId($item['remoteUserId']);
        $feed = $feedApi->createFeed(array(
            'content' => $content,
        ));
        return new JsonModel(array(
            'data' => $feed
        ));
    }
}
