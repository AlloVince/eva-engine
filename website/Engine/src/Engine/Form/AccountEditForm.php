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

namespace Engine\Form;

use User\Form\UserCreateForm;
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
            'Profile' => 'User\Form\ProfileForm',
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
    );

    public function beforeBind($data)
    {
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
        return $data;
    }

}
