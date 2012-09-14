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
class ProfileForm extends \Eva\Form\RestfulForm
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
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
        'site' => array (
            'name' => 'site',
            'type' => 'text',
            'options' => array (
                'label' => 'Site',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'photoDir' => array (
            'name' => 'photoDir',
            'type' => 'text',
            'options' => array (
                'label' => 'Photo Dir',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'photoName' => array (
            'name' => 'photoName',
            'type' => 'text',
            'options' => array (
                'label' => 'Photo Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'fullName' => array (
            'name' => 'fullName',
            'type' => 'text',
            'options' => array (
                'label' => 'Full Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'birthday' => array (
            'name' => 'birthday',
            'type' => 'text',
            'options' => array (
                'label' => 'Birthday',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'height' => array (
            'name' => 'height',
            'type' => 'text',
            'options' => array (
                'label' => 'Height',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'weight' => array (
            'name' => 'weight',
            'type' => 'text',
            'options' => array (
                'label' => 'Weight',
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
                'label' => 'Country',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'address' => array (
            'name' => 'address',
            'type' => 'text',
            'options' => array (
                'label' => 'Address',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'addressMore' => array (
            'name' => 'addressMore',
            'type' => 'text',
            'options' => array (
                'label' => 'Address More',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'city' => array (
            'name' => 'city',
            'type' => 'text',
            'options' => array (
                'label' => 'City',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'province' => array (
            'name' => 'province',
            'type' => 'text',
            'options' => array (
                'label' => 'Province',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'state' => array (
            'name' => 'state',
            'type' => 'text',
            'options' => array (
                'label' => 'State',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'zipcode' => array (
            'name' => 'zipcode',
            'type' => 'text',
            'options' => array (
                'label' => 'Zipcode',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'degree' => array (
            'name' => 'degree',
            'type' => 'text',
            'options' => array (
                'label' => 'Degree',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'industry' => array (
            'name' => 'industry',
            'type' => 'text',
            'options' => array (
                'label' => 'Industry',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'phoneBusiness' => array (
            'name' => 'phoneBusiness',
            'type' => 'text',
            'options' => array (
                'label' => 'Phone Business',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'phoneMobile' => array (
            'name' => 'phoneMobile',
            'type' => 'text',
            'options' => array (
                'label' => 'Phone Mobile',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'phoneHome' => array (
            'name' => 'phoneHome',
            'type' => 'text',
            'options' => array (
                'label' => 'Phone Home',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'fax' => array (
            'name' => 'fax',
            'type' => 'text',
            'options' => array (
                'label' => 'Fax',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'signature' => array (
            'name' => 'signature',
            'type' => 'text',
            'options' => array (
                'label' => 'Signature',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'longitude' => array (
            'name' => 'longitude',
            'type' => 'text',
            'options' => array (
                'label' => 'Longitude',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'latitude' => array (
            'name' => 'latitude',
            'type' => 'text',
            'options' => array (
                'label' => 'Latitude',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'location' => array (
            'name' => 'location',
            'type' => 'text',
            'options' => array (
                'label' => 'Location',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'bio' => array (
            'name' => 'bio',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Bio',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'localIm' => array (
            'name' => 'localIm',
            'type' => 'text',
            'options' => array (
                'label' => 'Local Im',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'internalIm' => array (
            'name' => 'internalIm',
            'type' => 'text',
            'options' => array (
                'label' => 'Internal Im',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'otherIm' => array (
            'name' => 'otherIm',
            'type' => 'text',
            'options' => array (
                'label' => 'Other Im',
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
        'user_id' => array (
            'name' => 'user_id',
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
        'site' => array (
            'name' => 'site',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'photoDir' => array (
            'name' => 'photoDir',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'photoName' => array (
            'name' => 'photoName',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'fullName' => array (
            'name' => 'fullName',
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
                        'max' => '200',
                    ),
                ),
            ),
        ),
        'birthday' => array (
            'name' => 'birthday',
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
                        'max' => NULL,
                    ),
                ),
            ),
        ),
        'height' => array (
            'name' => 'height',
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
                        'max' => '50',
                    ),
                ),
            ),
        ),
        'weight' => array (
            'name' => 'weight',
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
                        'max' => '50',
                    ),
                ),
            ),
        ),
        'country' => array (
            'name' => 'country',
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
                        'max' => '100',
                    ),
                ),
            ),
        ),
        'address' => array (
            'name' => 'address',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'addressMore' => array (
            'name' => 'addressMore',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'city' => array (
            'name' => 'city',
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
                        'max' => '100',
                    ),
                ),
            ),
        ),
        'province' => array (
            'name' => 'province',
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
                        'max' => '100',
                    ),
                ),
            ),
        ),
        'state' => array (
            'name' => 'state',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'zipcode' => array (
            'name' => 'zipcode',
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
        'degree' => array (
            'name' => 'degree',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'industry' => array (
            'name' => 'industry',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'phoneBusiness' => array (
            'name' => 'phoneBusiness',
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
        'phoneMobile' => array (
            'name' => 'phoneMobile',
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
        'phoneHome' => array (
            'name' => 'phoneHome',
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
        'fax' => array (
            'name' => 'fax',
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
        'signature' => array (
            'name' => 'signature',
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
                        'max' => '100',
                    ),
                ),
            ),
        ),
        'longitude' => array (
            'name' => 'longitude',
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
                        'max' => '50',
                    ),
                ),
            ),
        ),
        'latitude' => array (
            'name' => 'latitude',
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
                        'max' => '50',
                    ),
                ),
            ),
        ),
        'location' => array (
            'name' => 'location',
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
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'bio' => array (
            'name' => 'bio',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'localIm' => array (
            'name' => 'localIm',
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
                        'max' => '50',
                    ),
                ),
            ),
        ),
        'internalIm' => array (
            'name' => 'internalIm',
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
                        'max' => '250',
                    ),
                ),
            ),
        ),
        'otherIm' => array (
            'name' => 'otherIm',
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
                        'max' => '250',
                    ),
                ),
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
