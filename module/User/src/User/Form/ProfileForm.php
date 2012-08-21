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
class ProfileForm extends \Eva\Form\Form
{
    /**
     * Form basic elements
     *
     * @var array
     */
    protected $baseElements = array (
        'user_id' => array (
            'name' => 'user_id',
            'attributes' => array (
                'type' => 'hidden',
                'label' => 'User_id',
                'value' => '',
            ),
        ),
        'site' => array (
            'name' => 'site',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Site',
                'value' => '',
            ),
        ),
        'photoDir' => array (
            'name' => 'photoDir',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Photo Dir',
                'value' => '',
            ),
        ),
        'photoName' => array (
            'name' => 'photoName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Photo Name',
                'value' => '',
            ),
        ),
        'fullName' => array (
            'name' => 'fullName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Full Name',
                'value' => '',
            ),
        ),
        'birthday' => array (
            'name' => 'birthday',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Birthday',
                'value' => '',
            ),
        ),
        'height' => array (
            'name' => 'height',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Height',
                'value' => '',
            ),
        ),
        'weight' => array (
            'name' => 'weight',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Weight',
                'value' => '',
            ),
        ),
        'country' => array (
            'name' => 'country',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Country',
                'value' => '',
            ),
        ),
        'address' => array (
            'name' => 'address',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Address',
                'value' => '',
            ),
        ),
        'addressMore' => array (
            'name' => 'addressMore',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Address More',
                'value' => '',
            ),
        ),
        'city' => array (
            'name' => 'city',
            'attributes' => array (
                'type' => 'text',
                'label' => 'City',
                'value' => '',
            ),
        ),
        'province' => array (
            'name' => 'province',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Province',
                'value' => '',
            ),
        ),
        'state' => array (
            'name' => 'state',
            'attributes' => array (
                'type' => 'text',
                'label' => 'State',
                'value' => '',
            ),
        ),
        'zipcode' => array (
            'name' => 'zipcode',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Zipcode',
                'value' => '',
            ),
        ),
        'phoneBusiness' => array (
            'name' => 'phoneBusiness',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Phone Business',
                'value' => '',
            ),
        ),
        'phoneMobile' => array (
            'name' => 'phoneMobile',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Phone Mobile',
                'value' => '',
            ),
        ),
        'phoneHome' => array (
            'name' => 'phoneHome',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Phone Home',
                'value' => '',
            ),
        ),
        'fax' => array (
            'name' => 'fax',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Fax',
                'value' => '',
            ),
        ),
        'signature' => array (
            'name' => 'signature',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Signature',
                'value' => '',
            ),
        ),
        'longitude' => array (
            'name' => 'longitude',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Longitude',
                'value' => '',
            ),
        ),
        'latitude' => array (
            'name' => 'latitude',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Latitude',
                'value' => '',
            ),
        ),
        'location' => array (
            'name' => 'location',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Location',
                'value' => '',
            ),
        ),
        'bio' => array (
            'name' => 'bio',
            'attributes' => array (
                'type' => 'textarea',
                'label' => 'Bio',
                'value' => '',
            ),
        ),
        'localIm' => array (
            'name' => 'localIm',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Local Im',
                'value' => '',
            ),
        ),
        'internalIm' => array (
            'name' => 'internalIm',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Internal Im',
                'value' => '',
            ),
        ),
        'otherIm' => array (
            'name' => 'otherIm',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Other Im',
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
}

