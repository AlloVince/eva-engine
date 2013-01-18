<?php
namespace Event\Form;

class EventCreateForm extends EventForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Text' => 'Event\Form\TextForm',
            'EventFile' => 'Event\Form\EventFileForm',
            'CategoryEvent' => array(
                'formClass' => 'Event\Form\CategoryEventForm',
                'collection' => true,
                'optionsCallback' => 'initCategories',
            ),
            'Tags' => array(
                'formClass' => 'Event\Form\TagsForm',
                'collection' => true,
            ),
        ),
    );

    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'title' => array(
            'required' => true,
        ),
        'urlName' => array (
            'required' => false,
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'urlName',
                        'table' => 'event_events',
                    ),
                ),
            ),
        ),
    );
    
    public function beforeBind($data)
    {
        if(isset($data['Tags'][0]['tagName'])){
            $tagString = $data['Tags'][0]['tagName'];
            $tags = array();
            if(false === strpos($tagString, ',')) {
                $tags[] = array(
                    'tagName' => $tagString
                );
            } else {
                $tagNames = explode(',', $tagString);
                foreach($tagNames as $tag){
                    $tags[] = array(
                        'tagName' => $tag
                    );
                }
            }
            $data['Tags'] = $tags;
        }

        //Data is array is for display
        if(isset($data['CategoryEvent']) && is_array($data)){
            $categoryEvents = array();
            $subForms = $this->get('CategoryEvent');
            foreach($subForms as $key => $subForm){
                $categoryEvent = array();
                $category = $subForm->getCategory();
                if (!$category) {
                    continue;
                }
                $category = $category->toArray();
                $categoryEvent['category_id'] = $category['id'];
                foreach($data['CategoryEvent'] as $categoryEventArray){
                    if($categoryEvent['category_id'] == $categoryEventArray['category_id']){
                        $categoryEvent = array_merge($categoryEvent, $categoryEventArray);
                        break;
                    }
                }
                $categoryEvents[] = $categoryEvent;
            }
            $data['CategoryEvent'] = $categoryEvents;
        }
        return $data;
    }

    public function prepareData($data)
    {
        if (isset($data['startDay'])) {
            $time = new \DateTime($data['startDay']);
            $data['startDay'] = $time->format('Y-m-d');
        }
        
        if (isset($data['endDay'])) {
            $time = new \DateTime($data['endDay']);
            $data['endDay'] = $time->format('Y-m-d');
        }

        if (isset($data['endTime'])) {
            $time = new \DateTime($data['endTime']);
            $data['endTime'] = $time->format('H:i:s');
        }
        
        if (isset($data['startTime'])) {
            $time = new \DateTime($data['startTime']);
            $data['startTime'] = $time->format('H:i:s');
        }

        if (isset($data['endTime'])) {
            $startTime = new \DateTime($data['endTime']);
            $data['endTime'] = $startTime->format('H:i:s');
        }
        
        if(isset($data['EventFile'])){
            $data['EventFile']['event_id'] = $data['id'];
        }
        
        $categoryEvents = array();
        if(isset($data['CategoryEvent']) && $data['CategoryEvent']){
            foreach($data['CategoryEvent'] as $categoryEvent){
                if(isset($categoryEvent['category_id']) && $categoryEvent['category_id']){
                    $categoryEvents[] = $categoryEvent;
                }
            }
            $data['CategoryEvent'] = $categoryEvents;
        }

        return $data;
    }
}
