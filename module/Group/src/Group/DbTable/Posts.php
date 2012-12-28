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
        
        $groupsCategoriesTable = Api::_()->getDbTable('Group\DbTable\CategoriesGroups');
        $groupsCategoriesTableName = $groupsCategoriesTable->initTableName()->getTable();
        
        if($params->group_id || $params->groupCategory){
            $params->inGroup = true;
        }

        if($params->inGroup){
            $groupId = $params->group_id;
            $categoryId = $params->groupCategory;

            $this->where(function($where) use ($groupsPostsTableName, $groupsCategoriesTableName, $groupId, $categoryId){
                $select = new Select($groupsPostsTableName);
                $select->columns(array('post_id'));
                if($groupId){
                    $select->where(array(
                        'group_id' => $groupId
                    ));
                }
                
                if ($categoryId) {
                    $cateSelect = new Select($groupsCategoriesTableName);
                    $cateSelect->columns(array('group_id'));
                    $cateSelect->where(array(
                        'category_id' => $categoryId
                    )); 
                    $select->where(function($where) use ($cateSelect){
                        $where->in('group_id', $cateSelect);
                        return $where;
                    }); 
                }

                $where->in('id', $select);

                return $where;
            });
        }
        return parent::setParameters($params);
    }
}
