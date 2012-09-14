<?php

namespace User\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Fields extends TableGateway
{
    protected $tableName ='fields';
    protected $primaryKey = 'id';


    public function setParameters(Parameters $params)
    {
        $this->enableCount();

        if($params->applyToAll !== null){
            $this->where(array(
                'applyToAll' => $params->applyToAll,
            ));
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('userName', "%$keyword%");
                return $where;
            });
        }

        if ($params->rows) {
            $this->limit((int) $params->rows);
        }

        if($params->page){
            $this->page($params->page);
        }

        return $this;
    }
}
