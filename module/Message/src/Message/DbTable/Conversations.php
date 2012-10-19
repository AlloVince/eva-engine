<?php

namespace Message\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Conversations extends TableGateway
{
    protected $tableName ='conversations';
    protected $primaryKey = 'id';

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('title', "%$keyword%");
                return $where;
            });
        }

        if($params->author_id){
            $this->where(array('author_id' => $params->author_id));
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }
/*
        if($params->user_id){
            $userId = $params->user_id;
            $this->where(function($where) use ($userId){
                $subWhere = clone $where;

                $subWhere->equalTo('sender_id', $userId);
                $subWhere->or;
                $subWhere->equalTo('recipient_id', $userId);

                $where->addPredicate($subWhere);
                return $where;
            });
        }
 */
        if($params->page){
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'createTime ASC',
            'timedesc' => 'createTime DESC',
            'titleasc' => 'title ASC',
            'titledesc' => 'title DESC',
        );
        if($params->order){
            $order = $orders[$params->order];
            if($order){
                $this->order($order);
            }
        }

        return $this;
    }
}
