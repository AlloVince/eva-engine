<?php

namespace Video\Service\Adapter;
    
use Video\Service\Adapter\AdapterInterface;

class Youtube extends AbstractAdapter implements AdapterInterface
{
    protected $playerWidth = 480;
    protected $playerHeight = 295;

    public function getSwfUrl()
    {
        if($this->isValid()){
            return 'http://www.youtube.com/v/' . $this->getRemoteId();

        }
    }

    public function getThumbnail()
    {
        if($this->isValid()){
            return 'http://img.youtube.com/vi/' . $this->getRemoteId() . '/0.jpg';
        }
    }

    public function isValid()
    {
        if(true === $this->hasValid){
            return $this->isValid;
        }

        $this->hasValid = true;
        $url = $this->getUrl();

        $urlHandler = parse_url($url);
        $host = strtolower($urlHandler['host']);
        switch($host){
            case 'www.youtube.com':
            if(false === preg_match('#^http://www.youtube.com/watch?v=(\w+)#', $url, $matches)){
                return false;
            }
            break;
            case 'youtu.be':
            if(false === preg_match('#^http://youtu.be/(\w+)#', $url, $matches)){
                return false;
            }
            break;
            default:
            return false;
        }

        $this->remoteId = $matches[1];
        return $this->isValid = true;
    }

    public function toString()
    {
        if($this->isValid()){
            return 'http://www.youtube.com/watch?v=' . $this->getRemoteId();
        }
    }
}
