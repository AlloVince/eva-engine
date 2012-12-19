<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Activity\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class FollowForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
        'user_id' => array (
            'name' => 'user_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'User_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'follower_id' => array (
            'name' => 'follower_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Follower_id',
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
        'user_id' => array (
            'name' => 'user_id',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'follower_id' => array (
            'name' => 'follower_id',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );

    public function beforeBind($data)
    {
        $user = \Core\Auth::getLoginUser();
        $data['follower_id'] = $user['id'];
        return $data;
    }

}

