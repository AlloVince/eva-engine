<?php
namespace Blog\Model;

use Eva\Db\TableGateway\TableGateway,
    Eva\Db\Adapter\Adapter,
    Eva\Db\ResultSet\ResultSet;

class PostTable extends TableGateway
{
	protected $moduleName = 'blog';
	protected $tableName = 'posts';

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

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

    public function addPost($artist, $title)
    {
        $data = array(
            'urlName' => $artist,
            'title'  => $title,
        );
        $this->insert($data);
    }

    public function updatePost($id, $artist, $title)
    {
        $data = array(
            'urlName' => $artist,
            'title'  => $title,
        );
        $this->update($data, array('id' => $id));
    }

    public function deletePost($id)
    {
        $this->delete(array('id' => $id));
    }

}
