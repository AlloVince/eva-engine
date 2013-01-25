<?php

namespace Event\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;
use Eva\Api;
use Zend\Db\Sql\Expression;

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
       
        if($params->noResult) {
            $this->setNoResult(true);
        }

        if($params->id){
            if(is_array($params->id)){
                $this->where(array('id' => array_unique($params->id)));
            } else {
                $this->where(array('id' => $params->id));
            }
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
        
        if($params->recommend){
            $this->where(array('recommend' => $params->recommend));
        }

        if($params->visibility){
            $this->where(array('visibility' => $params->visibility));
        }
        
        if($params->city){
            $this->where(array('city' => $params->city));
        }
        
        if($params->memberEnable){
            $this->where(array('memberEnable' => $params->memberEnable));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if ($params->category) {
            $categoryModel = Api::_()->getModel('Event\Model\Category');
            $categoryItem = $categoryModel->getCategory($params->category);

            if ($categoryItem->id) {
                $categoryEventDb = Api::_()->getDbTable('Event\DbTable\CategoriesEvents');
                $categoryEventTabName = $categoryEventDb->initTableName()->table;
                $this->join(
                    $categoryEventTabName,
                    "{$this->table}.id = $categoryEventTabName.event_id",
                    array('*'),
                    'inner'
                );
                $this->where(array("$categoryEventTabName.category_id" => $categoryItem->id));
            } else {
                $this->where(array("id" => 0));
            }    
        }


        if($params->tag) {
            $tagModel = \Eva\Api::_()->getModel('Event\Model\Tag');
            $tag = $tagModel->getTag($params->tag);

            if($tag) {
                $tagId = $tag['id'];
                $tagPostTable = \Eva\Api::_()->getDbTable('Event\DbTable\TagsEvents'); 
                $tagPostTableName = $tagPostTable->initTableName()->getTable();

                $this->join(
                    $tagPostTableName,
                    "id = $tagPostTableName.event_id",
                    array('tag_id')
                ); 
                $this->where(array("$tagPostTableName.tag_id" => $tagId));
            } else {
                return false;
            }
        }

        if ($params->member_id) {
            $eventUserDb = Api::_()->getDbTable('Event\DbTable\EventsUsers');
            $eventUserTabName = $eventUserDb->initTableName()->table;
            $this->join(
                $eventUserTabName,
                "{$this->table}.id = $eventUserTabName.event_id",
                array('*'),
                'inner'
            );
            $this->where(array("$eventUserTabName.user_id" => $params->member_id));
        }

        if ($params->order == 'memberdesc' || $params->order == 'memberasc') {
            $eventCountDb = Api::_()->getDbTable('Event\DbTable\Counts');
            $eventCountTabName = $eventCountDb->initTableName()->table;
            $this->join(
                $eventCountTabName,
                "{$this->table}.id = $eventCountTabName.event_id",
                array('*'),
                'inner'
            );
        }
        
        if($params->noLimit) {
            $this->disableLimit();
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'createTime ASC',
            'timedesc' => 'createTime DESC',
            'titleasc' => 'title ASC',
            'titledesc' => 'title DESC',
            'memberdesc' => 'memberCount DESC',
            'memberasc' => 'memberCount ASC',
            'idarray' => 'FIELD(id, %s)',
        );

        if($params->order){
            $order = $orders[$params->order];
            if($order){
                if($params->order == 'idarray') {
                    if($params->id && is_array($params->id)){
                        $idArray = array_unique($params->id);
                        $order = sprintf($order, implode(',', array_fill(0, count($idArray), Expression::PLACEHOLDER)));
                        $this->order(array(new Expression($order, $idArray)));

                    }
                } else {
                    $this->order($order);
                }
            }
        }

        return $this;
    }
}
