<?php

namespace Group\Model;

use Blog\Model\Post as PostModel;

class Post extends PostModel
{
    protected $itemClass = 'Group\Item\Post';
    protected $groupPostPaginator;

    public function getGroupPostPaginator()
    {
        return $this->groupPostPaginator;
    }

    public function getGroupPostList($params)
    {
        $indexItem = $this->getItem('Group\Item\GroupPost');

        $defaultParams = array(
            'group_id' => '',
            'order' => 'iddesc',
            'page' => 1,
            'rows' => 20,
        );

        $itemQueryParams = array_merge($defaultParams, $params);

        $indexItem->collections($itemQueryParams);
        $this->groupPostPaginator = $indexItem->getPaginator();

        $postIdArray = array();
        foreach($indexItem as $index){
            $postIdArray[] = $index['post_id'];
        }
        if(!$postIdArray){
            $this->setItemList(array(
                'noResult' => true
            ));
        } else {
            $this->setItemList(array(
                'id' => $postIdArray,
                'order' => 'idarray',
                'noLimit' => true,
            ));
        }
        return $this;
    }

}
