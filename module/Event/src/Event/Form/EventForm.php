<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Event\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class EventForm extends \Eva\Form\Form
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
        'title' => array (
            'name' => 'title',
            'type' => 'text',
            'options' => array (
                'label' => 'Title',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'eventStatus' => array (
            'name' => 'eventStatus',
            'type' => 'select',
            'options' => array (
                'label' => 'Event Status',
                'value_options' => array (
                    'active' => array (
                        'label' => 'Active',
                        'value' => 'active',
                    ),
                    'pending' => array (
                        'label' => 'Pending',
                        'value' => 'pending',
                    ),
                    'deleted' => array (
                        'label' => 'Deleted',
                        'value' => 'deleted',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'active',
            ),
        ),
        'urlName' => array (
            'name' => 'urlName',
            'type' => 'text',
            'options' => array (
                'label' => 'Url Name',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'visibility' => array (
            'name' => 'visibility',
            'type' => 'select',
            'options' => array (
                'label' => 'Visibility',
                'value_options' => array (
                    'public' => array (
                        'label' => 'Public',
                        'value' => 'public',
                    ),
                    'private' => array (
                        'label' => 'Private',
                        'value' => 'private',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'public',
            ),
        ),
        'eventUsage' => array (
            'name' => 'eventUsage',
            'type' => 'text',
            'options' => array (
                'label' => 'Event Usage',
            ),
            'attributes' => array (
                'value' => 'other',
            ),
        ),
        'isFullDayEvent' => array (
            'name' => 'isFullDayEvent',
            'type' => 'checkbox',
            'options' => array (
                'label' => 'All Day',
            ),
            'attributes' => array (
                'value' => '1',
            ),
        ),
        'eventHash' => array (
            'name' => 'eventHash',
            'type' => 'text',
            'options' => array (
                'label' => 'Event Hash',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'startDay' => array (
            'name' => 'startDay',
            'type' => 'text',
            'options' => array (
                'label' => 'Start Day',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'startTime' => array (
            'name' => 'startTime',
            'type' => 'text',
            'options' => array (
                'label' => 'Start Time',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'endDay' => array (
            'name' => 'endDay',
            'type' => 'text',
            'options' => array (
                'label' => 'End Day',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'endTime' => array (
            'name' => 'endTime',
            'type' => 'text',
            'options' => array (
                'label' => 'End Time',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'timezone' => array (
            'name' => 'timezone',
            'type' => 'number',
            'options' => array (
                'label' => 'Timezone',
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'isRepeat' => array (
            'name' => 'isRepeat',
            'type' => 'checkbox',
            'options' => array (
                'label' => 'Repeat',
            ),
            'attributes' => array (
            ),
        ),
        'repeatStartDate' => array (
            'name' => 'repeatStartDate',
            'type' => 'text',
            'options' => array (
                'label' => 'Repeat Start Date',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'repeatEndDate' => array (
            'name' => 'repeatEndDate',
            'type' => 'text',
            'options' => array (
                'label' => 'Repeat End Date',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'frequency' => array (
            'name' => 'frequency',
            'type' => 'select',
            'options' => array (
                'label' => 'Frequency',
                'value_options' => array (
                    'daily' => array (
                        'label' => 'Daily',
                        'value' => 'daily',
                    ),
                    'weekly' => array (
                        'label' => 'Weekly',
                        'value' => 'weekly',
                    ),
                    'monthly' => array (
                        'label' => 'Monthly',
                        'value' => 'monthly',
                    ),
                    'yearly' => array (
                        'label' => 'Yearly',
                        'value' => 'yearly',
                    ),
                    'other' => array (
                        'label' => 'Other',
                        'value' => 'other',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'daily',
            ),
        ),
        'frequencyWeek' => array (
            'name' => 'frequencyWeek',
            'type' => 'text',
            'options' => array (
                'label' => 'Frequency Week',
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'frequencyMonth' => array (
            'name' => 'frequencyMonth',
            'type' => 'select',
            'options' => array (
                'label' => 'Frequency Month',
                'value_options' => array (
                    'dayofmonth' => array (
                        'label' => 'Day Of Month',
                        'value' => 'dayofmonth',
                    ),
                    'dayofweek' => array (
                        'label' => 'Day Of Week',
                        'value' => 'dayofweek',
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 'dayofweek',
            ),
        ),
        'interval' => array (
            'name' => 'interval',
            'type' => 'number',
            'options' => array (
                'label' => 'Repeat Interval',
            ),
            'attributes' => array (
                'value' => '0',
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
        'reminderType' => array (
            'name' => 'reminderType',
            'type' => 'select',
            'options' => array (
                'label' => 'Reminder Type',
                'value_options' => array (
                    'email' => array (
                        'label' => 'Email',
                        'value' => 'email',
                    ),
                    'alert' => array (
                        'label' => 'Alert',
                        'value' => 'alert',
                    ),
                    'sms' => array (
                        'label' => 'Sms',
                        'value' => 'sms',
                    ),
                ),
            ),
            'attributes' => array (
            ),
        ),
        'reminderTimeUnit' => array (
            'name' => 'reminderTimeUnit',
            'type' => 'select',
            'options' => array (
                'label' => 'Reminder Time Unit',
                'value_options' => array (
                    'minute' => array (
                        'label' => 'Minute',
                        'value' => 'minute',
                    ),
                    'hour' => array (
                        'label' => 'Hour',
                        'value' => 'hour',
                    ),
                    'day' => array (
                        'label' => 'Day',
                        'value' => 'day',
                    ),
                    'week' => array (
                        'label' => 'Week',
                        'value' => 'week',
                    ),
                ),
            ),
            'attributes' => array (
            ),
        ),
        'reminderTimeValue' => array (
            'name' => 'reminderTimeValue',
            'type' => 'text',
            'options' => array (
                'label' => 'Reminder Time Value',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'registrationStart' => array (
            'name' => 'registrationStart',
            'type' => 'text',
            'options' => array (
                'label' => 'Registration Start',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'registrationEnd' => array (
            'name' => 'registrationEnd',
            'type' => 'text',
            'options' => array (
                'label' => 'Registration End',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'country' => array (
            'name' => 'country',
            'type' => 'text',
            'options' => array (
                'label' => 'Country',
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
        'memberLimit' => array (
            'name' => 'memberLimit',
            'type' => 'text',
            'options' => array (
                'label' => 'Member Limit',
            ),
            'attributes' => array (
                'value' => '0',
            ),
        ),
        'memberEnable' => array (
            'name' => 'memberEnable',
            'type' => 'radio',
            'options' => array (
                'label' => 'Allow Join',
                'value_options' => array (
                    'yes' => array (
                        'label' => 'Yes',
                        'value' => 1,
                    ),
                    'no' => array (
                        'label' => 'No',
                        'value' => 0,
                    ),
                ),
            ),
            'attributes' => array (
                'value' => 1,
            ),
        ),
        'recommend' => array (
            'name' => 'recommend',
            'type' => 'radio',
            'options' => array (
                'label' => 'Recommend Event',
                'value_options' => array (
                    'yes' => array (
                        'label' => 'Yes',
                        'value' => 1,
                    ),
                    'no' => array (
                        'label' => 'No',
                        'value' => 0,
                    ),
                ),
            ),
            'attributes' => array (
                'value' => '0',
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
        'title' => array (
            'name' => 'title',
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'eventStatus' => array (
            'name' => 'eventStatus',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'active',
                            'finished',
                            'disputed',
                            'trashed',
                        ),
                    ),
                ),
            ),
        ),
        'urlName' => array (
            'name' => 'urlName',
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '255',
                    ),
                ),
            ),
        ),
        'visibility' => array (
            'name' => 'visibility',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'public',
                            'private',
                        ),
                    ),
                ),
            ),
        ),
        'eventUsage' => array (
            'name' => 'eventUsage',
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
        'isFullDayEvent' => array (
            'name' => 'isFullDayEvent',
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
        'eventHash' => array (
            'name' => 'eventHash',
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
        'startDay' => array (
            'name' => 'startDay',
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => NULL,
                    ),
                ),
            ),
        ),
        'startTime' => array (
            'name' => 'startTime',
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
        'endDay' => array (
            'name' => 'endDay',
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => NULL,
                    ),
                ),
            ),
        ),
        'endTime' => array (
            'name' => 'endTime',
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
        'timezone' => array (
            'name' => 'timezone',
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
        'isRepeat' => array (
            'name' => 'isRepeat',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                        ),
                    ),
                ),
            ),
        ),
        'repeatStartDate' => array (
            'name' => 'repeatStartDate',
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
        'repeatEndDate' => array (
            'name' => 'repeatEndDate',
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
        'frequency' => array (
            'name' => 'frequency',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'daily',
                            'weekly',
                            'monthly',
                            'yearly',
                            'other',
                        ),
                    ),
                ),
            ),
        ),
        'frequencyWeek' => array (
            'name' => 'frequencyWeek',
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                'stringLength' => array (
                    'name' => 'StringLength',
                    'options' => array (
                        'max' => '7',
                    ),
                ),
            ),
        ),
        'frequencyMonth' => array (
            'name' => 'frequencyMonth',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'dayofmonth',
                            'dayofweek',
                        ),
                    ),
                ),
            ),
        ),
        'interval' => array (
            'name' => 'interval',
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
        'reminderType' => array (
            'name' => 'reminderType',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'email',
                            'alert',
                            'sms',
                        ),
                    ),
                ),
            ),
        ),
        'reminderTimeUnit' => array (
            'name' => 'reminderTimeUnit',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'minute',
                            'hour',
                            'day',
                            'week',
                        ),
                    ),
                ),
            ),
        ),
        'reminderTimeValue' => array (
            'name' => 'reminderTimeValue',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'registrationStart' => array (
            'name' => 'registrationStart',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'registrationEnd' => array (
            'name' => 'registrationEnd',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
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
        'recommend' => array (
            'name' => 'recommend',
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
        'memberLimit' => array (
            'name' => 'memberLimit',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
        'memberEnable' => array (
            'name' => 'memberEnable',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
            ),
        ),
    );
}
