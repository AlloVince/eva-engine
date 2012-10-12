<?php
require_once './autoloader.php';
require_once EVA_LIB_PATH . '/Markdown/markdownextra.php';

$sm1 = new Zend\Session\SessionManager();
$sm1->setId(md5('session1'));
$sessionContainer1 = new Zend\Session\Container('Namespace', $sm1);
$sessionContainer1->offsetSet('testKey', 'foo');

echo $sessionContainer1->offsetGet('testKey'); //output foo


$sm2 = new Zend\Session\SessionManager();
$sm2->setId(md5('session2'));
/*
$sessionContainer2 = new Zend\Session\Container('Namespace', $sm2);
$sessionContainer2->offsetSet('testKey', 'bar');

echo $sessionContainer2->offsetGet('testKey'); //output bar
*/

