<?php
namespace Album\Model;

use Eva\Db\TableGateway\TableGateway,
    Eva\Db\Adapter\Adapter,
    Eva\Db\ResultSet\ResultSet;

class AlbumTable extends TableGateway
{
	protected $moduleName = 'album';
	protected $tableName = 'albums';

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function getAlbum($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function addAlbum($artist, $title)
    {
        $data = array(
            'urlName' => $artist,
            'title'  => $title,
        );
        $this->insert($data);
    }

    public function updateAlbum($id, $artist, $title)
    {
        $data = array(
            'urlName' => $artist,
            'title'  => $title,
        );
        $this->update($data, array('id' => $id));
    }

    public function deleteAlbum($id)
    {
        $this->delete(array('id' => $id));
    }

}
