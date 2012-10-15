<?php

namespace Payment\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Log extends AbstractModel
{
    protected $itemTableName = 'Payment\DbTable\Logs';


    public function getLog($id = null, array $map = array())
    {
        if(is_numeric($id)){
            $this->setItem(array(
                'id' => $id,
            ));
        } elseif(is_string($id)) {
            $item = $this->getItem()->getDataClass()->columns(array('id'))->where(array(
                'secretKey' => $id
            ))->find('one');
            if($item){
                $this->setItem(array(
                    'id' => $item['id'],
                ));
            }
        }

        $item = $this->getItem();
        if($map){
            $item = $item->toArray($map);
        } else {
            $item = $item->self(array('*'));
        }

        return $item;
    }

    public function getLogList(array $map = array())
    {

    }

    public function createLog($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $itemId = $item->create();

        return $itemId;
    }

    public function saveLog($data = null)
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();
        
        $item->save();
        
        if ($item->logStep == "response") {
            $this->trigger('logstep.response'); 
        }

        return $item->id;
    }

    public function removeLog()
    {
        $item = $this->getItem();
        $item->remove();

        return true;
    
    }


}
