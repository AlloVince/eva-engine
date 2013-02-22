<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class RoleUser extends AbstractModel
{
    public function getRoleUserList(array $itemListParameters = array(), $map = null)
    {
        $this->trigger('list.precache');

        $this->trigger('list.pre');

        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        $this->trigger('get');

        $this->trigger('list.post');
        $this->trigger('list.postcache');

        return $item;
    } 

    public function getRoleUser($userId = null, $roleId = null, array $map = array())
    {

        if ($userId && $roleId) {
            $this->setItem(array(
                'user_id' => $userId,
                'role_id' => $roleId,
            ));
        }

        $item = $this->getItem();
        if($map){
            $item = $item->toArray($map);
        } else {
            $item = $item->self(array('*'));
        }

        return $item;
    } 

    public function createRoleUser(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $itemId = $item->createRoleUser();

        return $itemId;
    }

    public function upgradeRoleUser($userId, $roleKey, $days)
    {
        $roleModel = Api::_()->getModel('User\Model\Role');
        $role = $roleModel->getRole($roleKey);

        if (!isset($role['id'])) {
            return array();
        }
        
        $roleUser = $this->getRoleUser($userId, $role['id']);

        $now = \Eva\Date\Date::getNow(); 
        
        if (!isset($roleUser['user_id'])) {
            $data['user_id']     = $userId;
            $data['role_id']     = $role['id'];
            $data['status']      = 'active';
            $data['activeTime']  = $now; 
            $data['expiredTime'] = \Eva\Date\Date::getFuture(3600 * 24 * $days, $now, 'Y-m-d H:i:s');
            $this->setItem($data)->createRoleUser();
        } else {
            $roleUser['status'] = 'active';
        
            if ($roleUser['expiredTime'] > $now) {
                $roleUser['expiredTime'] = \Eva\Date\Date::getFuture(3600 * 24 * $days, $roleUser['expiredTime'], 'Y-m-d H:i:s'); 
            } else {
                $roleUser['expiredTime'] = \Eva\Date\Date::getFuture(3600 * 24 * $days, $now, 'Y-m-d H:i:s'); 
                $roleUser['activeTime']  = $now; 
            }
            $roleUser->saveRoleUser();
        }
    }
}
