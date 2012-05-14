<?php
namespace Blog\Form;

use Eva\Form\Form,
    Eva\Form\Element;

class BlogForm extends Form
{
    public function init()
    {
        $this->setName('album');

        $id = new Element('id');
        $id->addFilter('Int');

        $artist = new Element('artist');
        $artist->setLabel('Artist')
               ->setRequired(true)
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('NotEmpty');

        $title = new Element('title');
        $title->setLabel('Title')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty');

        $submit = new Element('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $artist, $title, $submit));
    }
}
