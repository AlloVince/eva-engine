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

namespace Epic\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class ProfessionalProfileForm extends \User\Form\ProfileForm
{

    protected $mergeElements = array (
        'bio' => array (
            'options' => array (
                'label' => 'Few words about yourself',
            ),
        ),
        'industry' => array (
            'name' => 'industry',
            'type' => 'select',
            'options' => array (
                'label' => 'Industry',
                'value_options' => array (
                    array (
                        'label' => 'Food, Wine and Spirit Production',
                        'value' => 'Food, Wine and Spirit Production',
                    ),
                    array (
                        'label' => 'Hospitality',
                        'value' => 'Hospitality',
                    ),
                    array (
                        'label' => 'Restaurant, bar and club',
                        'value' => 'Restaurant, bar and club',
                    ),
                    array (
                        'label' => 'Distribution and Trade',
                        'value' => 'Distribution and Trade',
                    ),
                    array (
                        'label' => 'Media and Press',
                        'value' => 'Media and Press',
                    ),
                    array (
                        'label' => 'Marketing and Communication',
                        'value' => 'Marketing and Communication',
                    ),
                    array (
                        'label' => 'Consultancy',
                        'value' => 'Consultancy',
                    ),
                    array (
                        'label' => 'Educational Institution',
                        'value' => 'Educational Institution',
                    ),
                    array (
                        'label' => 'Research and Development',
                        'value' => 'Research and Development',
                    ),
                    array (
                        'label' => 'Other',
                        'value' => 'Other',
                    ),
                ),
            ),
        ),
        'phoneBusiness' => array (
            'options' => array (
                'label' => 'Phone',
            ),
        ),
    );

}
