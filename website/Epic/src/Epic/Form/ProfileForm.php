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
class ProfileForm extends \User\Form\ProfileForm
{

    public static $interest = array(
        array(
            'label' => 'Dining',
            'value' => 'Dining',
        ),
        array(
            'label' => 'Gastronomy',
            'value' => 'Gastronomy',
        ),
        array(
            'label' => 'Wine',
            'value' => 'Wine',
        ),
        array(
            'label' => 'Spirits',
            'value' => 'Spirits',
        ),
        array(
            'label' => 'Cocktail',
            'value' => 'Cocktail',
        ),
        array(
            'label' => 'Bar & Nightlife',
            'value' => 'Bar & Nightlife',
        ),
        array(
            'label' => 'Cooking',
            'value' => 'Cooking',
        ),
        array(
            'label' => 'Education',
            'value' => 'Education',
        ),
        array(
            'label' => 'Coffee & Tea',
            'value' => 'Coffee & Tea',
        ),
        array(
            'label' => 'Biologic & Organic',
            'value' => 'Biologic & Organic',
        ),
        array(
            'label' => 'Food',
            'value' => 'Food',
        ),
        array(
            'label' => 'Opinion Sharing',
            'value' => 'Opinion Sharing',
        ),
        array(
            'label' => 'Traveling',
            'value' => 'Traveling',
        ),
        array(
            'label' => 'Exhibition',
            'value' => 'Exhibition',
        ),
        array(
            'label' => 'Other',
            'value' => 'Other',
        ),
    );

    public static $city = array(
        array(
            'label' => 'EUROPE',
            'options' => array(
                array(
                    'label' => 'Amsterdam',
                    'value' => 'Amsterdam',
                ),
                array(
                    'label' => 'Athens',
                    'value' => 'Athens',
                ),
                array(
                    'label' => 'Barcelona',
                    'value' => 'Barcelona',
                ),
                array(
                    'label' => 'Berlin',
                    'value' => 'Berlin',
                ),
                array(
                    'label' => 'Brussels',
                    'value' => 'Brussels',
                ),
                array(
                    'label' => 'Budapest',
                    'value' => 'Budapest',
                ),
                array(
                    'label' => 'Copenhagen',
                    'value' => 'Copenhagen',
                ),
                array(
                    'label' => 'Dublin',
                    'value' => 'Dublin',
                ),
                array(
                    'label' => 'Florence',
                    'value' => 'Florence',
                ),
                array(
                    'label' => 'Lisbon',
                    'value' => 'Lisbon',
                ),
                array(
                    'label' => 'London',
                    'value' => 'London',
                ),
                array(
                    'label' => 'Madrid',
                    'value' => 'Madrid',
                ),
                array(
                    'label' => 'Milan',
                    'value' => 'Milan',
                ),
                array(
                    'label' => 'Paris',
                    'value' => 'Paris',
                ),
                array(
                    'label' => 'Prague',
                    'value' => 'Prague',
                ),
                array(
                    'label' => 'Rome',
                    'value' => 'Rome',
                ),
                array(
                    'label' => 'Stockholm',
                    'value' => 'Stockholm',
                ),
                array(
                    'label' => 'Venice',
                    'value' => 'Venice',
                ),
                array(
                    'label' => 'Vienna',
                    'value' => 'Vienna',
                ),
                array(
                    'label' => 'Zurich',
                    'value' => 'Zurich',
                ),
            ),
        ),

        array(
            'label' => 'Asia',
            'options' => array(
                array(
                    'label' => 'Beijing',
                    'value' => 'Beijing',
                ),
                array(
                    'label' => 'Chengdu',
                    'value' => 'Chengdu',
                ),
                array(
                    'label' => 'Chongqing',
                    'value' => 'Chongqing',
                ),
                array(
                    'label' => 'Guangzhou',
                    'value' => 'Guangzhou',
                ),
                array(
                    'label' => 'Hong Kong',
                    'value' => 'Hong Kong',
                ),
                array(
                    'label' => 'Shanghai',
                    'value' => 'Shanghai',
                ),
                array(
                    'label' => 'Shenzhen',
                    'value' => 'Shenzhen',
                ),
                array(
                    'label' => 'Tianjin',
                    'value' => 'Tianjin',
                ),
                array(
                    'label' => 'Xiamen',
                    'value' => 'Xiamen',
                ),
                array(
                    'label' => 'Xi an',
                    'value' => 'Xi an',
                ), 
                array(
                    'label' => 'Urumqi',
                    'value' => 'Urumqi',
                ),

            ),
        ),

        array(
            'label' => 'North American',
            'options' => array(
                array(
                    'label' => 'Boston',
                    'value' => 'Boston',
                ),
                array(
                    'label' => 'Chicago',
                    'value' => 'Chicago',
                ),
                array(
                    'label' => 'Las Vegas',
                    'value' => 'Las Vegas',
                ),
                array(
                    'label' => 'Los Angeles',
                    'value' => 'Los Angeles',
                ),
                array(
                    'label' => 'Miami',
                    'value' => 'Miami',
                ),
                array(
                    'label' => 'Montreal',
                    'value' => 'Montreal',
                ),
                array(
                    'label' => 'New York',
                    'value' => 'New York',
                ),
                array(
                    'label' => 'San Francisco',
                    'value' => 'San Francisco',
                ),
                array(
                    'label' => 'Seattle',
                    'value' => 'Seattle',
                ),
                array(
                    'label' => 'Washington D.C.',
                    'value' => 'Washington D.C.',
                ),

            ),
        ),

        array(
            'label' => 'Australia & Pacific',
            'options' => array(
                array(
                    'label' => 'Melbourne',
                    'value' => 'Melbourne',
                ),
                array(
                    'label' => 'Sydney',
                    'value' => 'Sydney',
                ),
            ),
        ),

        array(
            'label' => 'Central & South America',
            'options' => array(
                array(
                    'label' => 'Buenos Aires',
                    'value' => 'Buenos Aires',
                ),
                array(
                    'label' => 'Rio',
                    'value' => 'Rio',
                ),
                array(
                    'label' => 'San Paulo',
                    'value' => 'San Paulo',
                ),
            ),
        ),

        array(
            'label' => 'Mid East & Africa',
            'options' => array(
                array(
                    'label' => 'Capetown',
                    'value' => 'Capetown',
                ),
                array(
                    'label' => 'Jerusalem',
                    'value' => 'Jerusalem',
                ),
                array(
                    'label' => 'Tel Aviv',
                    'value' => 'Tel Aviv',
                ),
            ),
        ),

        array(
            'label' => 'Other',
            'options' => array(
                array(
                    'label' => 'Other',
                    'value' => 'Other',
                ),

            ),
        ),
    );


    /**
    * Form basic elements
    *
    * @var array
    */
    protected $mergeElements = array (
        'city' => array (
            'name' => 'city',
            'type' => 'select',
            'callback' => 'getCity',
            'options' => array (
                'label' => 'City',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        
        'country' => array (
            'name' => 'country',
            'type' => 'select',
            'callback' => 'getCountries',
            'options' => array (
                'label' => 'Nationality',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),

        'industry' => array (
            'name' => 'industry',
            'type' => 'text',
            'options' => array (
                'label' => 'Positions',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),

        'address' => array (
            'options' => array (
                'label' => 'Company Name',
            ),
        ),

        'interest' => array(
            'name' => 'interest',
            'type' => 'select',
            'callback' => 'getInterest',
            'options' => array (
                'label' => 'Center Interest',
                'empty_option' => 'Select Interest',
                //'value_options' => self::$interest,
            ),
        ),

    );

    protected $mergeFilters = array (
        'site' => array (
            'validators' => array (
                'uri' => array (
                    'name' => 'uri',
                    'options' => array (
                        'allowRelative' => false,
                    ),
                ),
            ),
        ),
    );

    public function getCity($element)
    {
        $element['options']['value_options'] = self::$city;
        return $element;
    }

    public function getInterest($element)
    {
        $element['options']['value_options'] = self::$interest;
        return $element;
    }

    public function getCountries($element)
    {
        $translator = \Eva\Api::_()->getServiceManager()->get('translator');
        $locale = $translator->getLocale();
        $countries = \Eva\Locale\Data::getList($locale, 'territory');

        if ($countries) {
            foreach ($countries as $key=>$country) {
                if (is_numeric($key)) {
                    unset($countries[$key]);
                } 
            }
        }
        $element['options']['value_options'] = $countries;
        return $element;
    }
}
