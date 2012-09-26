<?php

namespace File\DbTable;

use Eva\Db\TableGateway\TableGateway;
use Zend\Stdlib\Parameters;

class Files extends TableGateway
{
    protected $tableName ='files';

    protected $primaryKey = 'id';

    public function setParameters(Parameters $params)
    {
        if($params->page){
            $this->enableCount();
        }

        if($params->keyword){
            $keyword = $params->keyword;
            $this->where(function($where) use ($keyword){
                $where->like('title', "%$keyword%");
                return $where;
            });
        }

        if(!empty($params->isImage)){
            $this->where(array('isImage' => (string)$params->isImage));
        }

        if($params->status){
            $this->where(array('status' => $params->status));
        }

        if($params->fileExtension){
            $this->where(array('fileExtension' => $params->fileExtension));
        }

        if($params->fileSizeFrom){
            $this->where(array('fileSize > ?' => $params->fileSizeFrom));
        }
        if($params->fileSizeTo){
            $this->where(array('fileSize < ?' => $params->fileSizeTo));
        }

        if($params->imageWidthFrom){
            $this->where(array('imageWidth > ?' => $params->imageWidthFrom));
        }
        if($params->imageWidthTo){
            $this->where(array('imageWidth < ?' => $params->imageWidthTo));
        }


        if($params->imageHeightFrom){
            $this->where(array('imageHeight > ?' => $params->imageHeightFrom));
        }
        if($params->imageHeightTo){
            $this->where(array('imageHeight < ?' => $params->imageHeightTo));
        }


        if($params->page){
            $this->page($params->page);
        }

        $orders = array(
            'idasc' => 'id ASC',
            'iddesc' => 'id DESC',
            'timeasc' => 'createTime ASC',
            'timedesc' => 'createTime DESC',
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
