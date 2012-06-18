<?php
namespace Eva\Paginator;


class Paginator extends \Zend\Paginator\Paginator
{

    protected $rowCount;

    public function setRowCount($rowCount)
    {
        $this->rowCount = $rowCount;
        return $this;
    }

    protected function _calculatePageCount()
    {
        if($this->_rowCount){
            return (integer) ceil($this->rowCount / $this->getItemCountPerPage());
        }
        return (integer) ceil($this->getAdapter()->count() / $this->getItemCountPerPage());
    }
}

