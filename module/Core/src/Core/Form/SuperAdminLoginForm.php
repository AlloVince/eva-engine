<?
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Core\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class SuperAdminLoginForm extends \Eva\Form\Form
{
    protected $subFormGroups = array(
        'default' => array(
        ),
    );

    protected $mergeElements = array(
        'loginName' => array (
            'name' => 'loginName',
            'type' => 'text',
            'options' => array (
                'label' => 'User Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'inputPassword' => array (
            'name' => 'inputPassword',
            'type' => 'password',
            'options' => array (
                'label' => 'Password',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'rememberMe' => array (
            'name' => 'rememberMe',
            'type' => 'checkbox',
            'options' => array (
                'label' => 'Remember Me',
            ),
            'attributes' => array (
                'value' => '1',
            ),
        ),
    );

    protected $mergeFilters = array(
        'loginName' => array (
            'name' => 'loginName',
            'required' => true,
            'filters' => array (
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
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
        'rememberMe' => array (
            'name' => 'rememberMe',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );


    public function prepareData($data)
    {
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }
}
