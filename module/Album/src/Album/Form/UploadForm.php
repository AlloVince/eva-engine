<?php
namespace Album\Form;

use Zend\Form\Element;

class UploadForm extends \File\Form\UploadForm
{
    protected $subFormGroups = array(
        'default' => array(
            'AlbumFile' => 'Album\Form\AlbumFileForm',
        ),
    );

    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'upload' => array (
            'validators' => array (
                'fileExtension' => array (
                    'name' => 'File\Extension',
                    'options' => array (
                        'extension' => array('jpg', 'png', 'jpeg'),
                    ),
                ),
            ),
        ),
    );
}
