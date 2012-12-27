<?php

namespace Group\DbTable;

use Zend\Stdlib\Parameters;
use Eva\Api;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class Posts extends \Blog\DbTable\Posts
{
    public function initTableName()
    {
        $this->table = $this->getTablePrefix() . 'blog_' . $this->tableName;
        return $this;
    }

    public function setParameters(Parameters $params)
    {
        $groupsPostsTable = Api::_()->getDbTable('Group\DbTable\GroupsPosts');
        $groupsPostsTableName = $groupsPostsTable->initTableName()->getTable();

        if($params->group_id){
            $params->inGroup = true;
        }

        if($params->inGroup){
            $groupId = $params->group_id;

            $this->where(function($where) use ($groupsPostsTableName, $groupId){
                $select = new Select($groupsPostsTableName);
                $select->columns(array('post_id'));
                if($groupId){
                    $select->where(array(
                        'group_id' => $groupId
                    ));
                }
                $where->in('id', $select);
                return $where;
            });
        }
        return parent::setParameters($params);
    }
}
