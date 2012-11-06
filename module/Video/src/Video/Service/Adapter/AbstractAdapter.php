<?php
    
namespace Video\Service\Adapter;

abstract class AbstractAdapter
{
    protected $url;

    protected $remoteId;

    protected $playerWidth;

    protected $playerHeight;

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

    public function getPlayerSize()
    {
        return array(
            'width' => $this->playerWidth,
            'height' => $this->playerHeight,
        );
    }

    public function getPlayerWidth()
    {
        return $this->playerWidth;
    }

    public function getPlayerHeight()
    {
        return $this->playerHeight;
    }

    public function __construct($url)
    {
        $this->setUrl($url);
    }

}
