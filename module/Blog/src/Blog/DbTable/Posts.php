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
