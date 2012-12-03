<?php

namespace Webservice\Adapter\Feed;

interface FeedInterface
{
    public function getFeed($feedId);

    public function getFeedList($start, $rows);

    public function createFeed($content);

    public function createFeedWithPic($content, $pic);

    public function removeFeed($feedId);


}
