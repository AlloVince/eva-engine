<?php
namespace Group\Form;

class GroupCreateForm extends GroupForm
{
    protected $subFormGroups = array(
        'default' => array(
            'Text' => 'Group\Form\TextForm',
            'GroupFile' => 'Group\Form\GroupFileForm',
            'CategoryGroup' => array(
                'formClass' => 'Group\Form\CategoryGroupForm',
                'collection' => true,
                'optionsCallback' => 'initCategories',
            ),
            'Tags' => array(
                'formClass' => 'Group\Form\TagsForm',
                'collection' => true,
            ),
        ),
    );

    protected $mergeElements = array(
    );

    protected $mergeFilters = array(
        'groupName' => array(
            'required' => true,
        ),
        'groupKey' => array (
            'required' => false,
            'validators' => array (
                'db' => array(
                    'name' => 'Eva\Validator\Db\NoRecordExists',
                    'options' => array(
                        'field' => 'groupKey',
                        'table' => 'group_groups',
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
        if(isset($data['CategoryGroup']) && is_array($data)){
            $categoryGroups = array();
            $subForms = $this->get('CategoryGroup');
            foreach($subForms as $key => $subForm){
                $categoryGroup = array();
                $category = $subForm->getCategory();
                if (!$category) {
                    continue;
                }
                $category = $category->toArray();
                $categoryGroup['category_id'] = $category['id'];
                foreach($data['CategoryGroup'] as $categoryGroupArray){
                    if($categoryGroup['category_id'] == $categoryGroupArray['category_id']){
                        $categoryGroup = array_merge($categoryGroup, $categoryGroupArray);
                        break;
                    }
                }
                $categoryGroups[] = $categoryGroup;
            }
            $data['CategoryGroup'] = $categoryGroups;
        }
        return $data;
    }

    public function prepareData($data)
    {
        if(isset($data['GroupFile'])){
            $data['GroupFile']['group_id'] = $data['id'];
        }
        
        $categoryGroups = array();
        if(isset($data['CategoryGroup']) && $data['CategoryGroup']){
            foreach($data['CategoryGroup'] as $categoryGroup){
                if(isset($categoryGroup['category_id']) && $categoryGroup['category_id']){
                    $categoryGroups[] = $categoryGroup;
                }
            }
            $data['CategoryGroup'] = $categoryGroups;
        }

        return $data;
    }
}
