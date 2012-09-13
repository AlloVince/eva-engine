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
class FieldRoleForm extends \Eva\Form\RestfulForm
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'field_id' => array (
            'name' => 'field_id',
            'type' => 'hidden',
            'options' => array (
                'label' => 'Field_id',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'role_id' => array (
            'name' => 'role_id',
            'type' => 'multiCheckbox',
            'callback' => 'getUserRoles',
            'options' => array (
                'label' => 'User Roles',
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
        'field_id' => array (
            'name' => 'field_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'role_id' => array (
            'name' => 'role_id',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );


    public function getUserRoles($element)
    {
        $model = \Eva\Api::_()->getModelService('User\Model\Role');
        $items = $model->getRoleList();
        $valueOptions = array();
        foreach($items as $item){
            $valueOptions[] = array(
                'label' => $item['roleName'],
                'value' => $item['id'],
            );
        }
        $element['options']['value_options'] = $valueOptions;
        return $element;
    }
}
