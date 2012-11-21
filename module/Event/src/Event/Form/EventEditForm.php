<?php
namespace Event\Form;

use Eva\Form\Form;
use Zend\Form\Element;

class EventEditForm extends EventForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Text' => 'Event\Form\TextForm',
            'EventFile' => 'Event\Form\EventFileForm',
        ),
    );

    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'urlName' =>     array(
            'validators' => array(
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'injectdata' => true,
                    'options' => array(
                        'table' => 'event_events',
                        'field' => 'urlName',
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


   public function prepareData($data)
    {
        if(isset($data['EventFile'])){
            $data['EventFile']['event_id'] = $data['id'];
        }

        return $data;
    }
}
