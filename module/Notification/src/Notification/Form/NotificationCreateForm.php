<?php
namespace Notification\Form;

class NotificationCreateForm extends NotificationForm
{

    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'title' => array(
            'required' => true,
        ),
        'notificationKey' => array (
            'required' => false,
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'notificationKey',
                        'table' => 'notification_notifications',
                    ),
                ),
            ),
        ),
    );
}
