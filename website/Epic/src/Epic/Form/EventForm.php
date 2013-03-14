<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Epic\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class EventForm extends \Event\Form\EventForm
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
        'city' => array (
            'name' => 'city',
            'type' => 'select',
            'callback' => 'getCity',
            'attributes' => array (
                'value' => '',
            ),
        ),
    );

    public function getCity($element)
    {
        $element['options']['value_options'] = ProfileForm::$city;
        return $element;
    }
}
