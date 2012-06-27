<?php
namespace Eva\Mvc\Model;

abstract class AbstractItemList implements Iterator, ItemListInterface
{
    protected $items;

    public function __construct($dataSource)
    {
        $this->items = $dataSource;
    }
}
