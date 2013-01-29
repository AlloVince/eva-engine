<?php

namespace Notification\Model;

use Eva\Api;
use Eva\Mvc\Model\AbstractModel;
use User\Item\User;

class Notification extends AbstractModel
{
    protected $user;

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getGlobalSetting()
    {
        $defaultSetting = array(
            'sendNotice' => 0,
            'sendEmail' => 0,
            'sendSms' => 0,
            'sendAppleOsPush' => 0,
            'sendAndroidPush' => 0,
            'sendWindowsPush' => 0,
            'sendCustomPush' => 0,
            'allowDisableNotice' => 0,
            'allowDisableEmail' => 0,
            'allowDisableSms' => 0,
            'allowDisableAppleOsPush' => 0,
            'allowDisableAndroidPush' => 0,
            'allowDisableWindowsPush' => 0,
            'allowDisableCustomPush' => 0,
        );

        $item = $this->getItem();
        if($item->id){
            $item = $item->toArray();
        }

        $setting = array();
        foreach($defaultSetting as $key => $value){
            $setting[$key] = isset($item[$key]) && $item[$key] ? $item[$key] : 0;
        }

        return $setting;
    }

    public function getUserSetting()
    {
        $globalSetting = $this->getGlobalSetting();
        $defaultSetting = array(
            'disableNotice' => 0,
            'disableEmail' => 0,
            'disableSms' => 0,
            'disableAppleOsPush' => 0,
            'disableAndroidPush' => 0,
            'disableWindowsPush' => 0,
            'disableCustomPush' => 0,
        );
        if(!$this->user || !$this->user->id){
            return $this->mergeSetting($globalSetting, $defaultSetting);
        }

        $userSettingItem = $this->getItem('Notification\Item\Usersetting');
        $userSettingItem->user_id = $this->user->id;
        $userSettingItem->self(array('*'));
        if($userSettingItem){
            $userSettingItem = $userSettingItem->toArray();
        
        }
    }

    protected function mergeSetting($globalSetting, $userSetting)
    {
    
    }


    public function getNotification($idOrKey = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($idOrKey)){
            $this->setItem(array(
                'id' => $idOrKey,
            ));
        } elseif(is_string($idOrKey)) {
            $this->setItem(array(
                'notificationKey' => $idOrKey,
            ));
        }
        $this->trigger('get.pre');

        $item = $this->getItem();
        if($map){
            $item = $item->toArray($map);
        } else {
            $item = $item->self(array('*'));
        }

        $this->trigger('get');

        $this->trigger('get.post');
        $this->trigger('get.postcache');

        return $item;
    }

    public function getNotificationList(array $map = array())
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

    public function createNotification($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $itemId = $item->create();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->create();
            }
        }
        
        $this->trigger('create');
        $this->trigger('create.post');

        return $itemId;
    }

    public function saveNotification($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $this->trigger('save.pre');
        
        $item->save();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->save();
            }
        }
        $this->trigger('save');

        $this->trigger('save.post');

        return $item->id;
    }
    
    public function removeNotification()
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $item->remove();

        $this->trigger('remove');

        $this->trigger('remove.post');

        return true;
    }


}
