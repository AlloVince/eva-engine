<?php
namespace Album\Form;

class AlbumFileDeleteForm extends AlbumFileForm
{
    protected $validationAlbum = array('album_id','file_id');

    protected $mergeFilters = array(
        'album_id' => array(
            'name' => 'album_id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
        'file_id' => array(
            'name' => 'file_id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );
}
