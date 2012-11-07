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

        $res = array(
            'contactsCount'        => 0,
            'outSiteContactsCount' => 0,
            'onSiteContactsCount'  => 0,
            'onSiteFriendsCount'   => 0,
            'outSiteContacts'      => array(),
            'onSiteContacts'       => array(),
            'onSiteFriends'        => array(),
            $service => $contacts,
        );

        if (!$onSiteContacts) {
            $res['contactsCount']        = count($contacts);
            $res['outSiteContactsCount'] = count($outSiteContacts);
            $res['outSiteContacts']      = $outSiteContacts;
            return $res;
        }

        $onSiteFriends = array();
        foreach ($onSiteContacts as $key=>$user) {
            $onSiteFriends[$user['id']] = $user;
            unset($outSiteContacts[$user['email']]);
        }
        $onSiteContacts = $onSiteFriends;

        $itemModel = Api::_()->getModel('Activity\Model\Follow');
        $friends = $itemModel->setUserList($items)->setItemList(array(
            'follower_id' => $mine['id']
        ))->getFollowList()->toArray();
        if (!$friends) {
            $res['contactsCount']        = count($contacts);
            $res['outSiteContactsCount'] = count($outSiteContacts);
            $res['onSiteContactsCount']  = count($onSiteContacts);
            $res['outSiteContacts']      = $outSiteContacts;
            $res['onSiteContacts']       = $onSiteContacts;
            return $res;
        }
        $onSiteFriends = array();
        foreach ($friends as $friend) {
            if (isset($onSiteContacts[$friend['user_id']])) {
                $onSiteFriends[] = $onSiteContacts[$friend['user_id']];
                unset($onSiteContacts[$friend['user_id']]);
            } 
        }

        return array(
            'contactsCount'        => count($contacts),
            'outSiteContactsCount' => count($outSiteContacts),
            'onSiteContactsCount'  => count($onSiteContacts),
            'onSiteFriendsCount'   => count($onSiteFriends),
            'outSiteContacts'      => $outSiteContacts,
            'onSiteContacts'       => $onSiteContacts,
            'onSiteFriends'        => $onSiteFriends,
            $service               => $contacts,
        );   
    }
}
