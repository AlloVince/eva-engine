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
class RoleUserForm extends \Eva\Form\RestfulForm
{
    protected $role;

    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'role_id' => array (
            'name' => 'role_id',
            'type' => 'multiCheckbox',
            'options' => array (
                'label' => 'User Roles',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
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
        'status' => array (
            'name' => 'status',
            'type' => 'select',
            'options' => array (
                'label' => 'Status',
                'value_options' => array (
                    array (
                        'label' => 'Active',
                        'value' => 'active',
                    ),
                    array (
                        'label' => 'Pending',
                        'value' => 'pending',
                    ),
                    array (
                        'label' => 'Expired',
                        'value' => 'expired',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'pending',
            ),
        ),
        'pendingTime' => array (
            'name' => 'pendingTime',
            'type' => 'datetime',
            'options' => array (
                'label' => 'Pending Time',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'activeTime' => array (
            'name' => 'activeTime',
            'type' => 'datetime',
            'options' => array (
                'label' => 'Active Time',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'expiredTime' => array (
            'name' => 'expiredTime',
            'type' => 'datetime',
            'options' => array (
                'label' => 'Expired Time',
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
        'role_id' => array (
            'name' => 'role_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'user_id' => array (
            'name' => 'user_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'status' => array (
            'name' => 'status',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'active',
                            'pending',
                            'expired',
                        ),
                    ),
                ),
            ),
        ),
        'pendingTime' => array (
            'name' => 'pendingTime',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'activeTime' => array (
            'name' => 'activeTime',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'expiredTime' => array (
            'name' => 'expiredTime',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),

    );

    public function getRole()
    {
        if($this->role) {
            return $this->role;
        }

        $roleId = $this->get('role_id')->getValue();
        if(!$roleId){
            return array();
        }

        $model = \Eva\Api::_()->getModelService('User\Model\Role');
        $item = $model->getRole($roleId);
        return $item;
    }
}
