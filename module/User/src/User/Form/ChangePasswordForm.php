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
class ChangePasswordForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'id' => array (
            'name' => 'id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'verifyPassword' => array (
            'name' => 'verifyPassword',
            'type' => 'password',
            'options' => array (
                'label' => 'Old Password',
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
    protected $baseFilters = array (
        'id' => array (
            'name' => 'id',
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

        'verifyPassword' => array (
            'name' => 'verifyPassword',
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
                'callback' => array(
                    'name' => 'Callback',
                    'options' => array(
                        'callback' => array('User\Form\ChangePasswordForm', 'verifyPassword'),
                        'messages' => array(
                            \Zend\Validator\Callback::INVALID_VALUE => 'Password not match',
                        ),
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
                    'name' => 'Eva\Validator\EqualTo',
                    'injectdata' => true,
                    'options' => array (
                        'field' => 'inputPassword',
                    ),

                ),
            ),
        ),
    );

    public function prepareData($data)
    {
        $data['password'] = $data['inputPassword'];
        $data['oldPassword'] = $data['verifyPassword'];
        unset($data['verifyPassword'], $data['inputPassword'], $data['repeatPassword']);

        $userModel = \Eva\Api::_()->getModel('User\Model\User');
        $user = $userModel->getUser($data['id']);
        $salt = $user->salt;

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $bcrypt->setSalt($salt);
        $data['password'] = $bcrypt->create($data['password']);
        $data['oldPassword'] = $user->password;
        $data['lastPasswordChangeTime'] = \Eva\Date\Date::getNow();
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }

    public static function verifyPassword($password, $data)
    {
        $userModel = \Eva\Api::_()->getModel('User\Model\User');
        $user = $userModel->getUser($data['id']);
        $salt = $user->salt;

        $bcrypt = new \Zend\Crypt\Password\Bcrypt();
        $bcrypt->setSalt($salt);
        $verifyPassword = $bcrypt->create($password);

        if($verifyPassword === $user->password){
            return true;
        }
        return false;
    }
}
