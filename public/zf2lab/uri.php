<?php
require_once './autoloader.php';

$url = 'http://www.zf2.com/oauth/access/?callback=http://www.zf2.com/login/oauth/&service=douban&version=1&oauth_token=6259b011805e6018270d1034811b2048';

$zendUri = new \Zend\Uri\Http($url);
//p($zendUri);

p($_SERVER);
$request = new \Zend\Http\PhpEnvironment\Request();
//$request->setRequestUri($url);
p($request);
