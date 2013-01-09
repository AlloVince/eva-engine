<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;
use Eva\Api;
use Zend\Db\Sql\Expression;

class Users extends TableGateway
{
    protected $tableName ='users';
    protected $primaryKey = 'id';
    protected $uniqueIndex = array(
        'email',
        'mobile',
    );

    protected $profileTable;

    public function setParameters(Parameters $params)
    {
        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('userName', "%$keyword%");
                return $where;
            });
        }

        if($params->id){
            if(is_array($params->id)){
                $params->id = array_unique($params->id);
            }
            $this->where(array('id' => $params->id));
        }

        if($params->columns) {
            $this->columns($params->columns);
        }

        if($params->status){
            $this->where(array('status' => $params->status));
        }
        
        if($params->flag){
            $this->where(array('flag' => $params->flag));
        }

        if($params->gender){
            $this->where(array('gender' => $params->gender));
        }

        if($params->onlineStatus){
            $this->where(array('onlineStatus' => $params->onlineStatus));
        }

        if($params->city || $params->country || $params->industry) {
            $this->profileSelect($params);
        }

        if($params->emails){
            $emails = $params->emails;
            $this->where(function($where) use ($emails){
                $where->in('email', $emails);
                return $where;
            });
        }

        if ($params->role) {
            $roleModel = Api::_()->getModel('User\Model\Role');
            $roleItem = $roleModel->getRole($params->role);

            if ($roleItem->id) {
                $roleUserDb = Api::_()->getDbTable('User\DbTable\RolesUsers');
                $roleUserTabName = $roleUserDb->initTableName()->table;
                $this->join(
                    $roleUserTabName,
                    "{$this->initTableName()->table}.id = $roleUserTabName.user_id",
                    array('*'),
                    'inner'
                );
                $this->where(array("$roleUserTabName.role_id" => $roleItem->id));
            } else {
                $this->setNoResult(true);
            }    
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'registerTime ASC',
            'timedesc' => 'registerTime DESC',
            'nameasc' => 'userName ASC',
            'namedesc' => 'userName DESC',
            'followdesc' => 'MemberCount DESC',
        );
        
        if($params->order == 'followdesc'){
            $friendDb = Api::_()->getDbTable('User\DbTable\Friends');
            $friendTabName = $friendDb->initTableName()->table;
            $this->join(
                $friendTabName,
                "{$this->initTableName()->table}.id = $friendTabName.friend_id",
                array('*'),
                'left'
            );
            $this->where(array("$friendTabName.relationshipStatus" => 'approved'));
            $this->columns(array(
                '*',
                'MemberCount' => new Expression("count(`$friendTabName`.`user_id`)"),
            ));
            $this->group('friend_id');
            $this->order('MemberCount DESC');
            unset($params->order);
        }

        if($params->order){
            $order = $orders[$params->order];
            if($order){
                $this->order($order);
            }
        }

        return $this;
    }

    protected function profileSelect($params)
    {
        $profileTable = $this->getProfileTable();
        $profileTable->initialize();
        $profileTableName = $profileTable->getTable();
        $userTableName = $this->getTable();
        $this->join(
            $profileTableName,
            "id = $profileTableName.user_id"
        ); 

        if($params->city){
            $this->where(array("$profileTableName.city" => $params->city));
        }

        if($params->country){
            $this->where(array("$profileTableName.country" => $params->country));
        }

        if($params->industry){
            $this->where(array("$profileTableName.industry" => $params->industry));
        }

        return $this;
    }

    protected function getProfileTable()
    {
        if($this->profileTable){
            return $this->profileTable;
        }
        return $this->profileTable = Api::_()->getDbTable('User\DbTable\Profiles');
    }
}
