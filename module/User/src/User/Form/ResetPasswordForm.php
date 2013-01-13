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
class ResetPasswordForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
        'code' => array (
            'name' => 'code',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Verify Code',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'inputPassword' => array (
            'name' => 'inputPassword',
            'type' => 'password',
            'options' => array (
                'label' => 'New Password',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'repeatPassword' => array (
            'name' => 'repeatPassword',
            'type' => 'password',
            'options' => array (
                'label' => 'Repeat Password',
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
        'code' => array (
            'name' => 'code',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
            ),
        ),

        'inputPassword' => array (
            'name' => 'inputPassword',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'min' => '6',
                        'max' => '16',
                    ),
                ),
            ),
        ),
        'repeatPassword' => array (
            'name' => 'repeatPassword',
            'required' => true,
            'filters' => array (
            ),
            'validators' => array (
                'equalTo' => array(
                    'name' => 'Identical',
                    'options' => array (
                        'token' => 'inputPassword',
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME => 'Password not match',
                        ),
                    ),
                ),
            ),
        ),
    );

    public function prepareData($data)
    {
        $data['password'] = $data['inputPassword'];
        unset($data['inputPassword'], $data['repeatPassword']);
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }

}
