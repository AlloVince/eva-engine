<?php

namespace Video\Service\Adapter;
    
use Video\Service\Adapter\AdapterInterface;

class Youku extends AbstractAdapter implements AdapterInterface
{
    protected $playerWidth = 480;
    protected $playerHeight = 400;

    public function getSwfUrl()
    {
        if($this->isValid()){
            return 'http://player.youku.com/player.php/sid/' . $this->getRemoteId() . '/v.swf';
        }
    }

    public function getThumbnail()
    {
    }

    public function isValid()
    {
        if(true === $this->hasValid){
            return $this->isValid;
        }

        $this->hasValid = true;
        $url = $this->getUrl();

        if(false === preg_match('#^http://v.youku.com/v_show/id_(\w+).html#', $url, $matches)){
            return false;
        }

        $this->remoteId = $matches[1];
        return $this->isValid = true;
    }

    public function toString()
    {
        if($this->isValid()){
            return 'http://v.youku.com/v_show/id_' . $this->getRemoteId() . '.html';
        }
    }
}
