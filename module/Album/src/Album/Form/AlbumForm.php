<?php
namespace Album\Form;

use Zend\Form\Form,
    Zend\Form\Element;

class AlbumForm extends Form
{
    public function init()
    {
        $this->setName('album');

        $id = new Element\Hidden('id');
        $id->addFilter('Int');

        $artist = new Element\Text('artist');
        $artist->setLabel('Artist')
               ->setRequired(true)
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('NotEmpty');

        $title = new Element\Text('title');
        $title->setLabel('Title')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty');

        $submit = new Element\Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $artist, $title, $submit));
    }
}
