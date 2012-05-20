<?php
namespace Album\Form;

use Zend\Form\Factory as FormFactory;
use Zend\Form\Form;
use Zend\Form\Hydrator\ObjectProperty;

class AlbumForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setName('album');
        $this->setAttribute('method', 'post');

        $factory = new FormFactory();

        // Id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        // Artist        
        $this->add(array(
            'name' => 'artist',
            'attributes' => array(
                'type'  => 'text',
                'label' => 'Artist',
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'label' => 'Go',
                'id' => 'submitbutton',
            ),
        ));

    }
}
