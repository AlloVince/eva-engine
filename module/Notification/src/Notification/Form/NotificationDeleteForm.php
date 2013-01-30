<?php
namespace Notification\Form;

class NotificationDeleteForm extends NotificationForm
{
    protected $validationAlbum = array('id');

    protected $mergeFilters = array(
        'id' => array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
               array('name' => 'Int'),
            ),
        ),
    );
}
