<?php

namespace Blog\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;
use Zend\Db\Sql\Expression;
use Eva\Api;

class Tags extends TableGateway
{
    protected $tableName ='tags';
    protected $primaryKey = 'id';
    
    protected $uniqueIndex = array(
        'tagName',
    );

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
            $this->page($params->page);
        }

        if($params->noLimit) {
            $this->disableLimit();
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if ($params->order == 'postcountdesc' || $params->order == 'postcountasc') {
            $postTagTable = Api::_()->getDbTable('Blog\DbTable\TagsPosts');
            $postTagTableName = $postTagTable->initTableName()->getTable();

            $this->join(
                $postTagTableName,
                "id = $postTagTableName.tag_id"
            );
            $this->columns(array(
                '*',
                'PostCount' => new Expression("count(post_id)"),
            ));
            $this->group('tag_id');
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'nameasc' => 'tagName ASC',
            'namedesc' => 'tagName DESC',
            'postcountasc' => 'PostCount ASC',
            'postcountdesc' => 'PostCount DESC',
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
