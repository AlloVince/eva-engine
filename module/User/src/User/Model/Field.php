<?php

namespace User\Model;

use Eva\Api,
    Eva\Mvc\Model\AbstractModel;

class Field extends AbstractModel
{

    public function roleFieldsArrayToForm(array $roleArray)
    {
        $elements = array();
        if(isset($roleArray['CommonFields'])){
            $fields = $roleArray['CommonFields'];
            foreach($fields as $field){
                $elements[] = $this->fieldToElement($field);
            }
        }

        if(isset($roleArray['Fields'])){
            $fields = $roleArray['Fields'];
            foreach($fields as $field){
                $elements[] = $this->fieldToElement($field);
            }
        }

        return $elements;
    }

    public function fieldToElement($field)
    {
        $element = array(
            'name' => $field['id'],
            'type' => $field['fieldType'],
            'options' => array(
                'label' => $field['label'],
            ),
            'attributes' => array(
                'value' => $field['defaultValue'],
            ),
        );
        if(isset($field['Fieldoption']) && $field['Fieldoption']){
            $options = array();
            foreach($field['Fieldoption'] as $key => $option){
                $options[] = array(
                    'label' => $option['label'],
                    'value' => $option['option'],
                );
            }
            $element['options']['value_options'] = $options;
        }
        return $element;
    }

    public function fieldToFilter($field)
    {
        $filter = array(
            'name' => $field['id'],
            'required' => $field['required'] ? true : false,
        );
        return $filter;
    }

    public function createField(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('create.pre');

        $itemId = $item->create();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->create();
            }
        }
        $this->trigger('create');


        $this->trigger('create.post');

        return $itemId;
    }

    public function saveField(array $data = array())
    {
        if($data) {
            $this->setItem($data);
        }

        $item = $this->getItem();

        $this->trigger('save.pre');

        $item->save();

        if($item->hasLoadedRelationships()){
            foreach($item->getLoadedRelationships() as $key => $relItem){
                $relItem->save();
            }
        }
        $this->trigger('save');


        $this->trigger('save.post');


        return $item->id;

    }

    public function removeField(array $map = array())
    {
        $this->trigger('remove.pre');

        $item = $this->getItem();

        $subItem = $item->join('Fieldoption');
        $subItem->remove();

        $item->remove();

        $this->trigger('remove');

        $this->trigger('remove.post');

        return true;
    }

    public function getField($userIdOrName = null, array $map = array())
    {
        $this->trigger('get.precache');

        if(is_numeric($userIdOrName)){
            $this->setItem(array(
                'id' => $userIdOrName,
            ));
        } elseif(is_string($userIdOrName)) {
            $this->setItem(array(
                'fieldKey' => $userIdOrName,
            ));
        }
        $this->trigger('get.pre');

        $item = $this->getItem();
        if($map){
            $item = $item->toArray($map);
        } else {
            $item = $item->self(array('*'));
        }

        $this->trigger('get');

        $this->trigger('get.post');
        $this->trigger('get.postcache');

        return $item;
    }

    public function getFieldList(array $itemListParameters = array(), $map = null)
    {
        $this->trigger('list.precache');

        $this->trigger('list.pre');

        $item = $this->getItemList();
        if($map){
            $item = $item->toArray($map);
        }

        $this->trigger('get');

        $this->trigger('list.post');
        $this->trigger('list.postcache');

        return $item;
    }
}
