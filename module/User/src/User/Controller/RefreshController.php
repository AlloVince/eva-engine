<?php
namespace User\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Zend\View\Model\JsonModel,
    Core\Auth;

class RefreshController extends ActionController
{
    public function onlineAction()
    {
        $this->changeViewModel('json');
        $user = Auth::getLoginUser();
        if($user){
            $itemModel = Api::_()->getModel('User\Model\User');
            $dataClass = $itemModel->getItem()->getDataClass();
            $dataClass->where(array('id' => $user['id']))->save(array(
                'onlineStatus' => 'online',
                'lastFreshTime' => \Eva\Date\Date::getNow(),
            ));
            return new JsonModel();
        }
        return new JsonModel();
    }


    public function offlineAction()
    {
        $this->changeViewModel('json');
        $itemModel = Api::_()->getModel('User\Model\User');
        $config = Api::_()->getConfig();
        $onlineToOfflineTime = \Eva\Date\Date::getBefore($onlineToOfflineTime, null, 'Y-m-d H:i:s');

        $dataClass = $itemModel->getItem()->getDataClass();
        $dataClass->where(function($where) use ($onlineToOfflineTime) {
            $where->lessThan('lastFreshTime', $onlineToOfflineTime);
            return $where;
        })->save(array(
            'onlineStatus' => 'offline',
        ));
        return new JsonModel();
    }
}
