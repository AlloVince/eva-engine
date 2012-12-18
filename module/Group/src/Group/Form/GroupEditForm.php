<?php
namespace Group\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class GroupEditForm extends GroupCreateForm
{
    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'groupKey' =>     array(
            'validators' => array(
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'injectdata' => true,
                    'options' => array(
                        'table' => 'group_groups',
                        'field' => 'groupKey',
                        'exclude' => array(
                            'field' => 'id',
                        ),
                        'messages' => array(
                            'recordFound' => 'Abc',
                        ), 
                    ),
                ),
            ),
        ),
    );
}
