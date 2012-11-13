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
        
        $service = $this->service;   
        
        if ($service == 'msn') {
            return $this->getUserMsnContactsInfo($contacts);
        }
        
        $userModel = Api::_()->getModel('User\Model\User');
        $mine = $this->getUser();
        $mine = $userModel->getUser($mine['id']);

        if (!$mine) {
            return false;
        }

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

        $emails = array_unique($emails);

        $res = array(
            'contactsCount'        => count($emails),
            'outSiteContactsCount' => 0,
            'onSiteContactsCount'  => 0,
            'onSiteFriendsCount'   => 0,
            'outSiteContacts'      => array(),
            'onSiteContacts'       => array(),
            'onSiteFriends'        => array(),
            $service => $contacts,
        );
        
        if (count($emails) == 0) {
            return $res;
        }

        $selectQuery = array(
            'emails' => $emails,
            'rows' => 1000,
        );
        $items = $userModel->setItemList($selectQuery)->getUserList(); 
        $onSiteContacts = $items->toArray();

        if (!$onSiteContacts) {
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
            'contactsCount'        => count($emails),
            'outSiteContactsCount' => count($outSiteContacts),
            'onSiteContactsCount'  => count($onSiteContacts),
            'onSiteFriendsCount'   => count($onSiteFriends),
            'outSiteContacts'      => $outSiteContacts,
            'onSiteContacts'       => $onSiteContacts,
            'onSiteFriends'        => $onSiteFriends,
            $service               => $contacts,
        );   
    }

    protected function getUserMsnContactsInfo($contacts)
    {
        if (!$contacts) {
            return array();
        }
        
        $service = 'msn';  

        $userModel = Api::_()->getModel('User\Model\User');
        $mine = $this->getUser();
        $mine = $userModel->getUser($mine['id']);

        if (!$mine) {
            return false;
        }

        if (isset($contacts[$service])) {
            $contacts = $contacts[$service];
        }

        $res = array(
            'contactsCount'        => count($contacts),
            'outSiteContactsCount' => 0,
            'onSiteContactsCount'  => 0,
            'onSiteFriendsCount'   => 0,
            'outSiteContacts'      => array(),
            'onSiteContacts'       => array(),
            'onSiteFriends'        => array(),
            $service => $contacts,
        );

        $config = $this->getServiceLocator()->get('config');

        if (!isset($config['oauth']) 
            || !isset($config['oauth']['oauth2']) 
            || !isset($config['oauth']['oauth2']['msn']['consumer_key'])
        ) 
        {
            return $res;
        }
        
        $mineEmailHash = $this->getMsnEmailHash($mine['email'],$config['oauth']['oauth2']['msn']['consumer_key']);
        
        $emails = array();
        $outSiteContacts = array();   
        foreach ($contacts as $user) {
            if ($user['email'] == $mineEmailHash) {
                continue; 
            }
            $outSiteContacts[$user['email']] = $user;
            $emails[] = $user['email'];  
        }

        $emails = array_unique($emails);
        
        if (count($emails) == 0) {
            return $res;
        }

        $res['contactsCount'] = count($emails);

        $selectQuery = array(
            'emailMsnHashes' => $emails,
            'msnConsumerKey' => $config['oauth']['oauth2']['msn']['consumer_key'],
            'rows' => 1000,
        );

        $onSiteContacts = $this->getUserListByMsnHashes($selectQuery, $userModel);

        if (!$onSiteContacts) {
            return $res;
        }
        
        $onSiteFriends = array();
        foreach ($onSiteContacts as $key=>$user) {
            $onSiteFriends[$user['id']] = $user;
        }
        $onSiteContacts = $onSiteFriends; 

        $itemModel = Api::_()->getModel('Activity\Model\Follow');
        $friends = $itemModel->setItemList(array(
            'follower_id' => $mine['id']
        ))->getFollowList()->toArray();
        if (!$friends) {
            $res['onSiteContactsCount']  = count($onSiteContacts);
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

        $res['onSiteContactsCount']  = count($onSiteContacts);
        $res['onSiteContacts']       = $onSiteContacts;
        return $res;
    }

    protected function getMsnEmailHash($email, $msnConsumerKey)
    {
        return strtolower(hash('sha256', strtolower(($email . $msnConsumerKey))));
    }

    protected function getUserListByMsnHashes($query)
    {
        if (!$query) {
            return false;
        }

        $msnConsumerKey = $query['msnConsumerKey'];

        $db = Api::_()->getDbTable('User\DbTable\Users');
        $adapter = $db->getAdapter();
        $table = $db->getTable();

        $inString = "IN(";
        foreach ($query['emailMsnHashes'] as $hash) {
            $inString .= "'$hash', ";
        }
        $inString = strrev(substr(strrev($inString), 2));
        $inString .= ')';
        $selectString = "SELECT `$table`.* FROM `$table` WHERE LOWER(SHA2(LOWER(CONCAT(`$table`.`email`, '$msnConsumerKey')), 256)) $inString LIMIT 1000";

        return $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE)->toArray();
    }
}
