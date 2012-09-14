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

namespace User\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class UserSearchForm extends UserForm
{
    protected $mergeElements = array(
        'keyword' =>     array(
            'name' => 'keyword',
            'type' => 'text',
            'options' => array(
                'label' => 'Keyword',
            ),
            'attributes' => array(
            ),
        ),
        'status' => array(
            'options' => array(
                'value_options' => array(
                    array(
                        'label' => 'User Status',
                        'value' => '',
                    ),
                )
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'gender' => array(
            'options' => array(
                'value_options' => array(
                    array(
                        'label' => 'Select Gender',
                        'value' => '',
                    ),
                )
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'onlineStatus' => array(
            'options' => array(
                'value_options' => array(
                    array(
                        'label' => 'Online/Offline',
                        'value' => '',
                    ),
                )
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
    );
}
