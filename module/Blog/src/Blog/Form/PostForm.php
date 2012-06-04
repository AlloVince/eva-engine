<?php
namespace Blog\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Captcha\AdapterInterface as CaptchaAdapter;


class PostForm extends Form
{

    public function setCaptcha(CaptchaAdapter $captcha)
    {
        $this->captcha = $captcha;
    }

    public function __construct()
    {
        parent::__construct();

        $this->setName('post');
        $this->setAttribute('method', 'post');


        // Id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        // Title
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'label' => 'Post Title',
            ),
		));

        // Title
        $this->add(array(
            'name' => 'status',
            'attributes' => array(
                'type' => 'select',
				'options' => array(
					array(
						'label' => 'Draft',
						'value' => 'draft',
					),	
					array(
						'label' => 'Publish',
						'value' => 'publish',
					),	
				),
                'label' => 'Post Status',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
                'label' => 'Your email address',
            ),
        ));


		/*
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'label' => 'Your name',
            ),
        ));

        $this->add(array(
            'name' => 'subject',
            'attributes' => array(
                'type'  => 'text',
                'label' => 'Subject',
            ),
        ));
        $this->add(array(
            'name' => 'message',
            'attributes' => array(
                'type'  => 'textarea',
                'label' => 'Message',
            ),
		));
		 */

		/*
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'attributes' => array(
                'label' => 'Please verify you are human',
                'captcha' => array(
                    'class' => 'Dumb',
                ),
            ),
		));
		 */

        $this->add(new Element\Csrf('security'));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'label' => 'Submit',
            ),
        ));

    }
}
