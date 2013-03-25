<?php
namespace Epic\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class AlbumEditForm extends AlbumCreateForm
{
    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'urlName' =>     array(
            'validators' => array(
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'album_albums',
                        'field' => 'urlName',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                    ),
                ),
            ),
        ),
    );
}
