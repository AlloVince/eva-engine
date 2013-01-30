<?php
namespace Notification\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class NotificationEditForm extends NotificationCreateForm
{
    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'notificationKey' =>     array(
            'validators' => array(
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'injectdata' => true,
                    'options' => array(
                        'table' => 'notification_notifications',
                        'field' => 'notificationKey',
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
