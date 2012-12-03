<?php
    
namespace Webservice\Adapter\Feed;

use Webservice\Adapter\AbstractUniform;
use Webservice\Exception;

abstract class AbstractFeed extends AbstractUniform implements FeedInterface
{

    protected $feedId;

    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;
        return $this;
    }

    public function getFeed($feedId = null)
    {
        return $this->getData('Feed');
    }

    public function getFeedList($start, $rows = 10)
    {
        return $this->getData('FeedList');
    }

    public function createFeed($content)
    {
    
    }

    public function createFeedWithPic($content, $pic)
    {
    }

    public function removeFeed($feedId)
    {
    
    }

}
