<?php

namespace Video\Service\Adapter;
    
use Video\Service\Adapter\AdapterInterface;

class Youku implements AdapterInterface
{
    protected $url;

    protected $remoteId;

    protected $playerWidth = 480;

    protected $playerHeight = 400;

    protected $hasValid = false;

    protected $isValid = false;

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getRemoteId()
    {
        return $this->remoteId;
    }

    public function getSwfUrl()
    {
        if($this->isValid()){
            return 'http://player.youku.com/player.php/sid/' . $this->getRemoteId() . '/v.swf';
        }
    }

    public function getPlayerSize()
    {
        return array(
            'width' => $this->playerWidth,
            'height' => $this->playerHeight,
        );
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

    public function __construct($url)
    {
        $this->setUrl($url);
    }
}
