<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Posts extends TableGateway
{
    protected $tableName ='posts';

    protected $primaryKey = 'id';

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

        if($params->status){
            $this->where(array('status' => $params->status));
        }

        if($params->visibility){
            $this->where(array('visibility' => $params->visibility));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if ($params->category) {
            $cateModel = \Eva\Api::_()->getModel('Blog\Model\Category');
            $categoryinfo = $cateModel->setItemParams($params->category)->getCategory();
            
            $categoeyPostDb = \Eva\Api::_()->getDbTable('Blog\DbTable\CategoriesPosts'); 
            $categoeyPostTableName = $categoeyPostDb->initTableName()->getTable();

            if($categoryinfo) {
                $this->join(
                    $categoeyPostTableName,
                    "id = $categoeyPostTableName.post_id",
                    array('category_id'),
                    'inner'
                ); 
                $this->where(array("$categoeyPostTableName.category_id" => $categoryinfo['id']));
            } else {
				return false;
			}
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'updateTime ASC',
            'timedesc' => 'updateTime DESC',
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
