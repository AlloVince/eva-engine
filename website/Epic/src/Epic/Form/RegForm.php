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
class RegForm extends \Eva\Form\Form
{

    /**
     * Form basic elements
     *
     * @var array
     */
    protected $mergeElements = array (
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
        'email' => array (
            'name' => 'email',
            'type' => 'text',
            'options' => array (
                'label' => 'Email',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'firstName' => array (
            'name' => 'firstName',
            'type' => 'text',
            'options' => array (
                'label' => 'First Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'lastName' => array (
            'name' => 'lastName',
            'type' => 'text',
            'options' => array (
                'label' => 'Last Name',
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
        'id' => array (
            'name' => 'id',
            'required' => false,
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

        'email' => array (
            'name' => 'email',
            'required' => false,
            'filters' => array (
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '320',
                    ),
                ),
            ),
        ),

        'firstName' => array (
            'name' => 'firstName',
            'required' => false,
            'filters' => array (
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '20',
                    ),
                ),
            ),
        ),
        'lastName' => array (
            'name' => 'lastName',
            'required' => false,
            'filters' => array (
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '20',
                    ),
                ),
            ),
        ),

    );

    public function prepareData($data)
    {
        if(isset($data['UserRoleFields']) && is_array($data['UserRoleFields'])){
            $fieldvalues = array();
            foreach($data['UserRoleFields'] as $key => $fieldValue){
                if(!$fieldValue){
                    continue;
                }
                $fieldvalues[] = array(
                    'field_id' => $key,
                    'value' => \Zend\Json\Json::encode($fieldValue),
                );
            }
            $data['UserRoleFields'] = $fieldvalues;
        }

        if(isset($data['CommonFields']) && is_array($data['CommonFields'])){
            $fieldvalues = array();
            foreach($data['CommonFields'] as $key => $fieldValue){
                if(!$fieldValue){
                    continue;
                }
                $fieldvalues[] = array(
                    'field_id' => $key,
                    'value' => \Zend\Json\Json::encode($fieldValue),
                );
            }
            $data['UserCommonFields'] = $fieldvalues;
        }

        if($this->has('UserRoleFields')){
            $roleForm = $this->get('UserRoleFields');
            $roleId = $roleForm->getRole();
            $data['RoleUser'] = array(
                array(
                    'user_id' => null,
                    'role_id' => $roleId,
                )
            );
        }

        if(!isset($data['userName'])){
            $data['userName'] = $data['email'];
        }

        $data['status'] = 'inactive';
        $data['Account'] = array(
            'discount'  => 0,
            'credits' => 0,
            'points' => 0,
        );

        return $data;
    }
}
