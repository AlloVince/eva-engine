<?php
    require_once './autoloader.php';

    $fileTransfer = new Zend\File\Transfer\Transfer();
    $fileTransfer->addValidators(array(
        array('IsImage', true)
    ));
    if($fileTransfer->isValid()){
        $fileTransfer->receive();
    }
