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
}
