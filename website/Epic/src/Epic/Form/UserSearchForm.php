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
class UserSearchForm extends \User\Form\UserForm
{
    protected $mergeElements = array(
        'keyword' =>     array(
            'name' => 'keyword',
            'type' => 'text',
            'options' => array(
                'label' => 'Keyword',
            ),
            'attributes' => array(
            ),
        ),
        'order' =>     array(
            'name' => 'order',
            'type' => 'text',
            'options' => array(
                'label' => 'Order',
            ),
            'attributes' => array(
            ),
        ),
        'rows' =>     array(
            'name' => 'rows',
            'type' => 'text',
            'options' => array(
                'label' => 'Rows',
            ),
            'attributes' => array(
            ),
        ),
        'status' => array(
            'options' => array(
                'empty_option' => 'User Status'
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'gender' => array(
            'options' => array(
                'empty_option' => 'Select Gender'
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'onlineStatus' => array(
            'options' => array(
                'empty_option' => 'Online/Offline'
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'city' => array(
            'name' => 'city',
            'type' => 'select',
            'callback' => 'getCity',
            'options' => array(
                'empty_option' => 'Select City',
                'attributes' => array(
                    'value' => '',
                ),
            ),
        ),
        'country' => array(
            'name' => 'country',
            'type' => 'select',
            'callback' => 'getCountries',
            'options' => array(
                'empty_option' => 'Select Country'
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'industry' => array(
            'name' => 'industry',
            'type' => 'text',
            'options' => array(
                'label' => 'Text',
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'interest' => array(
            'name' => 'interest',
            'type' => 'select',
            'callback' => 'getInterest',
            'options' => array (
                'label' => 'Interest',
                'empty_option' => 'Select Interest',
            ),
        ),
        'role' => array(
            'name' => 'role',
            'type' => 'select',
            'options' => array (
                'label' => 'Role',
                'empty_option' => 'Member Type',
                'value_options' => array(
                    array(
                        'label' => 'Connoisseur',
                        'value' => 'CONNOISSEUR_MEMBER',
                    ),
                    array(
                        'label' => 'Professional',
                        'value' => 'PROFESSIONAL_MEMBER',
                    ),
                    array(
                        'label' => 'VIP',
                        'value' => 'PAID_MEMBER',
                    ),
                    array(
                        'label' => 'Corporate',
                        'value' => 'CORPORATE_MEMBER',
                    ),
                ),
            ),
        ),
        'tag' => array(
            'name' => 'tag',
            'type' => 'text',
            'options' => array(
                'label' => 'Text',
            ),
            'attributes' => array(
                'value' => '',
            ),
        ),
        'page' =>     array(
            'name' => 'page',
            'type' => 'text',
            'options' => array(
                'label' => 'Page',
            ),
            'attributes' => array(
                'value' => 1,
            ),
        ),
    );

    public function getCity($element)
    {
        $element['options']['value_options'] = ProfileForm::$city;
        return $element;
    }

    public function getInterest($element)
    {
        $element['options']['value_options'] = ProfileForm::$interest;
        return $element;
    }

    public function getCountries($element)
    {
        $translator = \Eva\Api::_()->getServiceManager()->get('translator');
        $locale = $translator->getLocale();
        $countries = \Eva\Locale\Data::getList($locale, 'territory');
        $element['options']['value_options'] = $countries;
        return $element;
    }

    public function prepareData($data)
    {
        if(!$data['page']){
            $data['page'] = 1;
        }

        if(!$data['order']) {
            $data['order'] = 'iddesc';
        }

        return $data;
    }
}
