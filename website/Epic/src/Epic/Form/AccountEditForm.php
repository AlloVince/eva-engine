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

use Epic\Form\UserCreateForm;
use Eva\Api;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class AccountEditForm extends UserCreateForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Profile' => 'Epic\Form\ProfileForm',
            'Tags' => array(
                'formClass' => 'User\Form\TagsForm',
                'collection' => true,
            ),
        ),
    );

    protected $validationGroup = array(
        'id',
        'firstName',
        'lastName',
        'gender',
        'avatar_id',
        'language',
        'role',
        'Profile' => array(
            'site',
            'birthday',
            'country',
            'address',
            'city',
            'province',
            'phoneMobile',
            'industry',
            'interest',
            'bio'
        ),
        'Tags',
    );

    protected $mergeElements = array(
        'timezone' => array (
            'callback' => 'getTimezones',
        ),
        'language' => array (
            'callback' => 'getLanguages',
        ),
        'inputPassword' => array (
            'name' => 'inputPassword',
            'type' => 'text',
            'options' => array (
                'label' => 'Password',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'role' => array (
            'name' => 'role',
            'type' => 'select',
            'options' => array (
                'label' => 'Register As',
                'value_options' => array (
                    /*
                    array (
                        'label' => 'Corporate Member',
                        'value' => 'CORPORATE_MEMBER',
                    ),
                    */
                    array (
                        'label' => 'Connoisseur',
                        'value' => 'CONNOISSEUR_MEMBER',
                    ),
                    array (
                        'label' => 'Professional',
                        'value' => 'PROFESSIONAL_MEMBER',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'CONNOISSEUR_MEMBER',
            ),
        ),
    );

    public function beforeBind($data)
    {
        if(isset($data['Roles'][0])){
            $allowRoles =  array (
               //'CORPORATE_MEMBER',
               'CONNOISSEUR_MEMBER',
               'PROFESSIONAL_MEMBER',
            );
            foreach($data['Roles'] as $role){
                if(in_array($role['roleKey'], $allowRoles)){
                    $data['role'] = $role['roleKey'];
                    break;
                }
            }
        }

        if(isset($data['Tags'][0]['tagName'])){
            $tagString = $data['Tags'][0]['tagName'];
            $tags = array();
            if(false === strpos($tagString, ',')) {
                $tags[] = array(
                    'tagName' => $tagString
                );
            } else {
                $tagNames = explode(',', $tagString);
                foreach($tagNames as $tag){
                    $tags[] = array(
                        'tagName' => $tag
                    );
                }
            }
            $data['Tags'] = $tags;
        }

        return $data;
    }


    public function prepareData($data)
    {
        $roleKey = $data['role'] ? $data['role'] : 'CONNOISSEUR_MEMBER';
        $itemModel = Api::_()->getModel('User\Model\Role');
        $role = $itemModel->getRole($roleKey);
        
        $data['RoleUser'] = array(
            array(
                'user_id' => null,
                'role_id' => $role->id,
                'status' => 'active',
            )
        );

        if ($data['id']) {
            $roleUserModel = Api::_()->getModel('User\Model\RoleUser');
            $userRoles = $roleUserModel->setItemList(array('user_id' => $data['id'],'noLimit' => true))->getRoleUserList();
            $excludeRoleKey = $roleKey == 'CONNOISSEUR_MEMBER' ? 'PROFESSIONAL_MEMBER' : 'CONNOISSEUR_MEMBER';
            $role = $role->toArray();
            $excludeRole = $itemModel->getRole($excludeRoleKey);
            $excludeRole = $excludeRole->toArray();
            if (count($userRoles) > 0) {
                foreach ($userRoles as $userRole) {
                    if ($userRole['role_id'] == $role['id'] || $userRole['role_id'] == $excludeRole['id']) {
                        continue;
                    }
                    $data['RoleUser'][] =array(
                        'user_id' => null,
                        'role_id' => $userRole['role_id'],
                        'status' => $userRole['status'],
                        'pendingTime' => $userRole['pendingTime'],
                        'activeTime' => $userRole['activeTime'],
                        'expiredTime' => $userRole['expiredTime'],
                    ); 
                }
            }
        }
        unset($data['role']);
        return $data;
    }

}
