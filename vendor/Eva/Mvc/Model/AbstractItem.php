<?php
namespace Eva\Mvc\Model;

class AbstractItem 
{
    protected $item;

    protected $model;

    protected $config;

    public function toArray($config = array())
    {
        $item = $this->item;

        $defaultConfig = $this->config;
        $config = array_merge($defaultConfig, $config);

        foreach ($item as $key => $value) {
            $methodName = 'get' . ucfirst($key);
            if(true === method_exists($this, $methodName)){
                $this->$methodName($value);
            }
        }
        return $this->item;
    }

    public function __construct($item, $model = null, $config = array())
    {
        $this->item = $item;
        $this->model = $model;
        $this->config = $config;
    }
}
