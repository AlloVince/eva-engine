<?php
    require_once './autoloader.php';

    $config =  array(
        'name' => 'codeType',
        'attributes' => array(
            'type'  => 'radio',
            'label' => 'Code Type',
            'options' => array(
                'Markdown' => 'markdown',
                'HTML' => 'html',
                'Wiki' => 'wiki',
            ),
            'value' => array('markdown'),
        ),
    );

    $factory = new Zend\Form\Factory();
    $element = $factory->create($config);
    $helper = new Zend\Form\View\Helper\FormMultiCheckbox();
    echo $helper->render($element);
