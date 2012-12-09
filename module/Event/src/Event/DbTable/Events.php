<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Events extends TableGateway
{
    protected $tableName ='events';

    protected $primaryKey = 'id';

    protected $uniqueIndex = array(
        'urlName',
    );

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        if($params->user_id){
            $this->where(array('user_id' => $params->user_id));
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('title', "%$keyword%");
                return $where;
            });
        }
        
        if($params->beforeStartDay){
            $beforeStartDay = $params->beforeStartDay;
            $this->where(function($where) use ($beforeStartDay){
                $where->lessThanOrEqualTo('startDay', $beforeStartDay);
                return $where;
            });
        }
        
        if($params->afterStartDay){
            $afterStartDay = $params->afterStartDay;
            $this->where(function($where) use ($afterStartDay){
                $where->greaterThanOrEqualTo('startDay', $afterStartDay);
                return $where;
            });
        }
        
        if($params->beforeEndDay){
            $beforeEndDay = $params->beforeEndDay;
            $this->where(function($where) use ($beforeEndDay){
                $where->lessThanOrEqualTo('endDay', $beforeEndDay);
                return $where;
            });
        }
        
        if($params->afterEndDay){
            $afterEndDay = $params->afterEndDay;
            $this->where(function($where) use ($afterEndDay){
                $where->greaterThanOrEqualTo('endDay', $afterEndDay);
                return $where;
            });
        }

        if($params->eventStatus){
            $this->where(array('eventStatus' => $params->eventStatus));
        }

        if($params->visibility){
            $this->where(array('visibility' => $params->visibility));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'startDatetimeUtc ASC',
            'timedesc' => 'startDatetimeUtc DESC',
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
