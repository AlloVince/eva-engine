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
class UserSearchForm extends UserForm
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
            'type' => 'text',
            'options' => array(
                'empty_option' => 'Select City'
            ),
            'attributes' => array(
                'value' => '',
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
            'options' => array (
                'label' => 'Industry',
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
    );


    public function getCountries($element)
    {
        $translator = \Eva\Api::_()->getServiceManager()->get('translator');
        $locale = $translator->getLocale();
        $countries = \Eva\Locale\Data::getList($locale, 'territory');
        $element['options']['value_options'] = $countries;
        return $element;
    }
}
