<?php

namespace Album\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class CategoriesAlbums extends TableGateway
{
    protected $tableName ='categories_albums';
    protected $primaryKey = array('category_id', 'album_id');

    public function setParameters(Parameters $params)
    {
        if($params->album_id){
            $this->where(array('album_id' => $params->album_id));
        }

        if($params->category_id){
            $this->where(array('category_id' => $params->category_id));
        }
        
        if($params->noLimit) {
            $this->disableLimit();
        }

        return $this;
    }
}
