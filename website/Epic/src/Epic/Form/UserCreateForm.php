<?php
namespace Epic\Form;

use User\Form\UserForm;
use Eva\Form\Form;
use Zend\Form\Element;

class UserCreateForm extends UserForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Profile' => 'Epic\Form\ProfileForm',
            'Account' => 'User\Form\AccountForm',
        ),
    );

    protected $mergeElements = array(
        'timezone' => array (
            'callback' => 'getTimezones',
        ),
        'language' => array (
            'callback' => 'getLanguages',
        ),
    );

    protected $mergeFilters = array(
        'userName' => array (
            'required' => true,
        ),
        'email' => array (
            'required' => true,
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'injectdata' => true,
                    'options' => array(
                        'field' => 'email',
                        'table' => 'user_users',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                    ),
                ),
            ),
        ),
    );

    public function prepareData($data)
    {
        if(isset($data['inputPassword'])){
            $data['password'] = $data['inputPassword'];
            unset($data['inputPassword']);
        }
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }

    public function getLanguages($element)
    {
        $translator = \Eva\Api::_()->getServiceManager()->get('translator');
        $locale = $translator->getLocale();
        $languages = \Eva\Locale\Data::getList($locale, 'language');
        $element['options']['value_options'] = $languages;
        $element['attributes']['value'] = $locale;
        return $element;
    }

    public function getTimezones($element)
    {
        $translator = \Eva\Api::_()->getServiceManager()->get('translator');
        $locale = $translator->getLocale();
        $languages = \Eva\Locale\Data::getList($locale, 'citytotimezone');
        $element['options']['value_options'] = $languages;
        return $element;
    }
}
