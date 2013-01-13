<?php
namespace Album\Form;

class AlbumDeleteForm extends AlbumForm
{
    protected $validationAlbum = array('id');

    protected $mergeFilters = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );
}
