<?php
require_once './autoloader.php';

$_SERVER['REQUEST_URI'] = '/html/index.php?url=http://test.example.com/path/&foo=bar';
$request = new \Zend\Http\PhpEnvironment\Request();
//$request->setRequestUri($url);
echo $request->getRequestUri();
