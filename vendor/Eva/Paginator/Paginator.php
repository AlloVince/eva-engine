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

    public function toArray()
    {
        $itemCountPerPage = $this->getItemCountPerPage();
        $rowCount = $this->rowCount;
        $pageCount = $this->count();
        $currentPageNumber = $this->getCurrentPageNumber();
        $offsetStart = ($currentPageNumber - 1) * $itemCountPerPage + 1;
        $offsetEnd = $currentPageNumber == $pageCount ? $rowCount : $currentPageNumber * $itemCountPerPage ;
        if($currentPageNumber >= $pageCount){
            $nextPage = false;
        } else {
            $nextPage = $currentPageNumber + 1 > $pageCount ? $pageCount : $currentPageNumber + 1;
        }

        if($currentPageNumber <= 1){
            $prevPage = false;
        } else {
            $prevPage = $currentPageNumber - 1 < 1 ? 1 : $currentPageNumber - 1;
        }

        $pageRange = $this->getPageRange();

        $i = 0;
        $prevPageRange = array();
        if($currentPageNumber > 1){
            $i = $currentPageNumber - $pageRange;
            $i = $i <= 1 ? 1 : $i;
            for ($i; $i < $currentPageNumber; $i++) {
                $prevPageRange[] = $i;
            }
        }
        $nextPageRange = array();
        if($currentPageNumber < $pageCount){
            $limit = $currentPageNumber + $pageRange;
            $limit = $limit >= $pageCount ? $pageCount : $limit;
            $i = $currentPageNumber + 1;
            for($i; $i <= $limit; $i++){
                $nextPageRange[] = $i;
            }
        }

        return array(
            'itemCountPerPage' => $itemCountPerPage,
            'rowCount' => $rowCount,
            'pageNumber' => $currentPageNumber,
            'offsetStart' => $offsetStart,
            'offsetEnd' => $offsetEnd,
            'pageCount' => $pageCount,
            'nextPage' => $nextPage,
            'prevPage' => $prevPage,
            'pageRage' => $pageRange,
            'prevPageRange' => $prevPageRange,
            'nextPageRange' => $nextPageRange,
        );
    }

    protected function _calculatePageCount()
    {
        if($this->rowCount){
            return (integer) ceil($this->rowCount / $this->getItemCountPerPage());
        }
        return (integer) ceil($this->getAdapter()->count() / $this->getItemCountPerPage());
    }
}

