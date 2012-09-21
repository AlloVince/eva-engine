<?php
namespace Blog\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class PostForm extends Form
{
    protected $fieldsMap = array(
        
    );

    protected $baseElements = array(
        'id' => array (
            'name' => 'id',
            'attributes' => array (
                'type' => 'hidden',
                'label' => 'Id',
                'value' => '',
            ),
        ),
        'title' => array (
            'name' => 'title',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Title',
                'value' => '',
            ),
        ),
        'status' => array (
            'name' => 'status',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Status',
                'options' => array (
                    array (
                        'label' => 'Deleted',
                        'value' => 'deleted',
                    ),
                    array (
                        'label' => 'Draft',
                        'value' => 'draft',
                    ),
                    array (
                        'label' => 'Published',
                        'value' => 'published',
                    ),
                    array (
                        'label' => 'Pending',
                        'value' => 'pending',
                    ),
                ),
                'value' => 'published',
            ),
        ),
        'visibility' => array (
            'name' => 'visibility',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Visibility',
                'options' => array (
                    array (
                        'label' => 'Public',
                        'value' => 'public',
                    ),
                    array (
                        'label' => 'Private',
                        'value' => 'private',
                    ),
                    array (
                        'label' => 'Password',
                        'value' => 'password',
                    ),
                ),
            ),
        ),
        'codeType' => array (
            'name' => 'codeType',
            'attributes' => array (
                'type' => 'multiCheckbox',
                'label' => 'Code Type',
                'options' => array (
                    array (
                        'label' => 'Markdown',
                        'value' => 'markdown',
                    ),
                    array (
                        'label' => 'Html',
                        'value' => 'html',
                    ),
                    /*
                    array (
                        'label' => 'Wiki',
                        'value' => 'wiki',
                    ),
                    array (
                        'label' => 'Ubb',
                        'value' => 'ubb',
                    ),
                    array (
                        'label' => 'Other',
                        'value' => 'other',
                    ),
                    */
                ),
                'value' => 'markdown',
            ),
        ),
        'language' => array (
            'name' => 'language',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Language',
                'value' => 'en',
            ),
        ),


        'urlName' => array (
            'name' => 'urlName',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Post Url',
                'value' => '',
            ),
        ),

        'postPassword' => array (
            'name' => 'postPassword',
            'attributes' => array (
                'type' => 'text',
                'label' => 'Post Password',
                'value' => '',
            ),
        ),


        'commentStatus' => array (
            'name' => 'commentStatus',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Comment Status',
                'options' => array (
                    array (
                        'label' => 'Open',
                        'value' => 'open',
                    ),
                    array (
                        'label' => 'Closed',
                        'value' => 'closed',
                    ),
                    array (
                        'label' => 'Authority',
                        'value' => 'authority',
                    ),
                ),
                'value' => 'open',
            ),
        ),
        'commentType' => array (
            'name' => 'commentType',
            'attributes' => array (
                'type' => 'select',
                'label' => 'Comment Type',
                'options' => array (
                    array (
                        'label' => 'Local',
                        'value' => 'local',
                    ),
                    array (
                        'label' => 'Disqus',
                        'value' => 'disqus',
                    ),
                    array (
                        'label' => 'Youyan',
                        'value' => 'youyan',
                    ),
                    array (
                        'label' => 'Duoshuo',
                        'value' => 'duoshuo',
                    ),
                ),
                'value' => 'local',
            ),
        ),

    );

    /**
     * Form basic Validators
     *
     * @var array
     */
    protected $baseFilters = array (
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Title not allow empty',
                        ), 
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
        'status' => array (
            'name' => 'status',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'deleted',
                            'draft',
                            'published',
                            'pending',
                        ),
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
                'notEmpty' => array (
                    'name' => 'NotEmpty',
                    'options' => array (
                    ),
                ),
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'public',
                            'private',
                            'password',
                        ),
                    ),
                ),
            ),
        ),
        'codeType' => array (
            'name' => 'codeType',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'markdown',
                            'html',
                            'wiki',
                            'ubb',
                            'other',
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
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'eva_blog_posts',
                        'messages' => array(
                            //\Zend\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'Abc',
                        ), 
                    ),
                ),
            ),
        ),

        'postPassword' => array (
            'name' => 'postPassword',
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
                        'max' => '32',
                    ),
                ),
            ),
        ),
        'commentStatus' => array (
            'name' => 'commentStatus',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'open',
                            'closed',
                            'authority',
                        ),
                    ),
                ),
            ),
        ),
        'commentType' => array (
            'name' => 'commentType',
            'required' => false,
            'filters' => array (
            ),
            'validators' => array (
                'inArray' => array (
                    'name' => 'InArray',
                    'options' => array (
                        'haystack' => array (
                            'local',
                            'disqus',
                            'youyan',
                            'duoshuo',
                        ),
                    ),
                ),
            ),
        ),
    );
}
