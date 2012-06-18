<?php

namespace Blog\Model;

use Eva\Mvc\Model\AbstractModel;

class Post extends AbstractModel
{
    protected $itemTableName = 'Blog\DbTable\Posts';

    public function getPost($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePost(Post $album)
    {
        $data = array(
            'artist' => $album->artist,
            'title'  => $album->title,
        );

        $id = (int)$album->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getPost($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exit');
            }
        }
    }

    public function addPost($artist, $title)
    {
        $data = array(
            'artist' => $artist,
            'title'  => $title,
        );
        $this->insert($data);
    }

    public function updatePost($id, $artist, $title)
    {
        $data = array(
            'artist' => $artist,
            'title'  => $title,
        );
        $this->update($data, array('id' => $id));
    }

    public function deletePost($id)
    {
        $this->delete(array('id' => $id));
    }

}
