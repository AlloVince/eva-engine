<?
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Core\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class SendEmailForm extends \Eva\Form\Form
{
    protected $subFormGroups = array(
        'default' => array(
        ),
    );

    protected $baseElements = array(
        'sender' => array (
            'name' => 'sender',
            'type' => 'text',
            'options' => array (
                'label' => 'Email Sender',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'recipient' => array (
            'name' => 'recipient',
            'type' => 'text',
            'options' => array (
                'label' => 'Email Recipient',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'subject' => array (
            'name' => 'subject',
            'type' => 'text',
            'options' => array (
                'label' => 'Subject',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'content' => array (
            'name' => 'content',
            'type' => 'textarea',
            'options' => array (
                'label' => 'Content',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'attachment' => array (
            'name' => 'attachment',
            'type' => 'file',
            'options' => array(
                'label' => 'Attachment',
            ),
            'attributes' => array (
            ),
        ),
    );

    protected $baseFilters = array(
        'sender' => array (
            'name' => 'sender',
            'required' => false,
            'filters' => array (
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
            ),
        ),
        'recipient' => array (
            'name' => 'recipient',
            'required' => false,
            'filters' => array (
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
            ),
        ),
        'subject' => array (
            'name' => 'subject',
            'required' => true,
            'filters' => array (
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
            ),
        ),
        'content' => array (
            'name' => 'content',
            'required' => true,
            'filters' => array (
                'stripTags' => array (
                    'name' => 'StripTags',
                ),
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
            ),
        ),
        'attachment' => array (
            'name' => 'attachment',
            'required' => false,
            'options' => array(
                'ignoreNoFile' => true,
            ),
            'filters' => array (
                array (
                    'name' => '\Eva\Filter\File\AutoRename',
                    'options' => array(
                        'configkey' => 'default',
                    ),
                ),
            ),
            'validators' => array (
                /*`
                array (
                    'name' => 'File\Extension',
                    'options' => array (
                        'extension' => array('txt'),
                    ),
                ),
                */
            ),
        ),

    );


    public function prepareData($data)
    {
        return $data;
    }

    public function beforeBind($data)
    {
        return $data;
    }
}
