<?php

namespace Album\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;
use Eva\Api;

class Albums extends TableGateway
{
    protected $tableName ='albums';

    protected $primaryKey = 'id';

    protected $uniqueIndex = array(
        'albumKey',
    );

    public function setParameters(Parameters $params)
    {
        if($params->id){
            if(is_array($params->id)){
                $this->where(array('id' => array_unique($params->id)));
            } else {
                $this->where(array('id' => $params->id));
            }
        }
        
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
                $where->like('albumName', "%$keyword%");
                return $where;
            });
        }

        if($params->status){
            $this->where(array('status' => $params->status));
        }
        
        if($params->recommend){
            $this->where(array('recommend' => $params->recommend));
        }

        if($params->memberEnable){
            $this->where(array('memberEnable' => $params->memberEnable));
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if ($params->category) {
            $categoryModel = Api::_()->getModel('Album\Model\Category');
            $categoryItem = $categoryModel->getCategory($params->category);

            if ($categoryItem->id) {
                $categoryAlbumDb = Api::_()->getDbTable('Album\DbTable\CategoriesAlbums');
                $categoryAlbumTabName = $categoryAlbumDb->initTableName()->table;
                $this->join(
                    $categoryAlbumTabName,
                    "{$this->table}.id = $categoryAlbumTabName.album_id",
                    array('*'),
                    'inner'
                );
                $this->where(array("$categoryAlbumTabName.category_id" => $categoryItem->id));
            } else {
                $this->where(array("id" => 0));
            }    
        }

        if ($params->order == 'memberdesc' || $params->order == 'memberasc') {
            $albumCountDb = Api::_()->getDbTable('Album\DbTable\Counts');
            $albumCountTabName = $albumCountDb->initTableName()->table;
            $this->join(
                $albumCountTabName,
                "{$this->table}.id = $albumCountTabName.album_id",
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
            'titleasc' => 'albumName ASC',
            'titledesc' => 'albumName DESC',
            'memberdesc' => 'memberCount DESC',
            'memberasc' => 'memberCount ASC',
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
