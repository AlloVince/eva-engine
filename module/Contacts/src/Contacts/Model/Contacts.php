<?php

namespace Contacts\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Contacts extends AbstractModel
{
    protected $user;
    
    protected $service;
    
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getService()
    {
        return $this->user;
    }

    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    public function getUserContactsInfo($contacts)
    {
        if (!$contacts) {
            return array();
        }

        $userModel = Api::_()->getModel('User\Model\User');
        $mine = $this->getUser();
        $mine = $userModel->getUser($mine['id']);

        if (!$mine) {
            return false;
        }

        $service = $this->service;   

        if (isset($contacts[$service])) {
            $contacts = $contacts[$service];
        }

        $emails = array();
        $outSiteContacts = array();   
        foreach ($contacts as $user) {
            if ($user['email'] == $mine['email']) {
                continue; 
            }
            $outSiteContacts[$user['email']] = $user;
            $emails[] = $user['email'];  
        }

        $selectQuery = array(
            'emails' => $emails,
            'rows' => 1000,
        );
        $items = $userModel->setItemList($selectQuery)->getUserList(); 
        $onSiteContacts = $items->toArray();

        if (!$onSiteContacts) {
            return array(
                'contactsCount' => count($contacts),
                'outSiteContactsCount' => count($outSiteContacts),
                'outSiteContacts' => $outSiteContacts,
            );
        }

        $onSiteFriends = array();
        foreach ($onSiteContacts as $key=>$user) {
            $onSiteFriends[$user['id']] = $user;
            unset($outSiteContacts[$user['email']]);
        }
        $onSiteContacts = $onSiteFriends;

        $selectQuery = array(
            'from_user_id' => $mine['id'],
            'relationshiopStatus' => 'approved',
            'rows' => 1000,
        );

        $itemModel = Api::_()->getModel('User\Model\Friend');
        $items = $itemModel->setItemList($selectQuery)->getFriendList();
        $friends = $items->toArray();

        if (!$friends) {
            return array(
                'contactsCount' => count($contacts),
                'outSiteContactsCount' => count($outSiteContacts),
                'onSiteContactsCount' => count($onSiteContacts),
                'outSiteContacts' => $outSiteContacts,
                'onSiteContacts' => $onSiteContacts,
            );   
        }

        $onSiteFriends = array();
        foreach ($friends as $friend) {
            if (isset($onSiteContacts[$friend['to_user_id']])) {
                $onSiteFriends = $onSiteContacts[$friend['to_user_id']];
                unset($onSiteContacts[$friend['to_user_id']]);
            } 
        }

        if (!$friends) {
            return array(
                'contactsCount' => count($contacts),
                'outSiteContactsCount' => count($outSiteContacts),
                'onSiteContactsCount' => count($onSiteContacts),
                'onSiteFriendsCount' => $onSiteFriendsCount,
                'outSiteContacts' => $outSiteContacts,
                'onSiteContacts' => $onSiteContacts,
                'onSiteFriends' => $onSiteFriends,
                $service => $contacts,
            );   
        }
    }
}
