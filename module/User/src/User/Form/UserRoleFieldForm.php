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
class UserRoleFieldForm extends \Eva\Form\RestfulForm
{
    protected $role;
    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    public function __construct($formName = null, $role)
    {
        $this->setRole($role);
        parent::__construct($formName);
    }

    public function init()
    {
        $roleKey = $this->getRole();

        $itemModel = \Eva\Api::_()->getModelService('User\Model\Role');
        $item = $itemModel->getRole($roleKey);
        $item = $item->toArray(array(
            'self' => array(
                '*'
            ),
            'join' => array(
                'RoleFields' => array(
                    'self' => array(
                        '*'
                    ),
                    'join' => array(
                        'Fieldoption' => array(
                            'self' => array(
                                '*'
                            ),
                        ),
                    ),
                ),
            )
        ));
        if(isset($item['Fields'])){
            $fieldModel = \Eva\Api::_()->getModelService('User\Model\Field');
            $elements = array();
            foreach($item['Fields'] as $field){
                $elements[] = $fieldModel->fieldToElement($field);
            }
            $this->setMergeElements($elements);
            parent::init();
        }
    }
}
