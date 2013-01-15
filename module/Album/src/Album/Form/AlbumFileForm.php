<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Album\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class AlbumFileForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
        'file_id' => array (
            'name' => 'file_id',
            'type' => NULL,
            'options' => array (
                'label' => 'File_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'album_id' => array (
            'name' => 'album_id',
            'type' => NULL,
            'options' => array (
                'label' => 'Album_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $mergeFilters = array (
        'file_id' => array (
            'name' => 'file_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'album_id' => array (
            'name' => 'album_id',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
