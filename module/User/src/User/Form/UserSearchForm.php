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
            'attributes' => array(
                'type' => 'text',
                'label' => 'Keyword',
            ),
        ),
        'status' => array(
            'attributes' => array(
                'options' => array(
                    array(
                        'label' => 'User Status',
                        'value' => '',
                    ),
                ),
                'value' => '',
            ),
        ),
        'gender' => array(
            'attributes' => array(
                'options' => array(
                    array(
                        'label' => 'Select Gender',
                        'value' => '',
                    ),
                ),
                'value' => '',
            ),
        ),
        'onlineStatus' => array(
            'attributes' => array(
                'options' => array(
                    array(
                        'label' => 'Online/Offline',
                        'value' => '',
                    ),
                ),
                'value' => '',
            ),
        ),
    );
}
