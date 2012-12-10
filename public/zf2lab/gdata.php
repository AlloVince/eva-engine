<?php
require_once './autoloader.php';
$loader->registerNamespace('ZendGData\\', EVA_PUBLIC_PATH . '/../vendor/ZendGdata/library/ZendGData');

$user = "allo.vince@gmail.com";
$pass = "password";
$userId = '104171418568283484752';
$albumId = '5819073682310479025';
 
$client = \ZendGData\ClientLogin::getHttpClient($user, $pass, \ZendGData\Photos::AUTH_SERVICE_NAME);

$service = new \ZendGData\Photos($client);
$fileSource = $service->newMediaFileSource('D:\xampp\htdocs\zf2\public/static/upload\e7\6a\b4\YrYN3m.gif');
$fileSource->setContentType('image/jpeg');
$fileSource->setSlug('test.jpg');

$entry = new \ZendGData\Photos\PhotoEntry();
$entry->setMediaSource($fileSource);
$entry->setTitle($service->newTitle('test'));

$albumQuery = new \ZendGData\Photos\AlbumQuery();
$albumQuery->setUser($userId);
$albumQuery->setAlbumId($albumId);
$albumEntry = $service->getAlbumEntry($albumQuery);

try {
    $service->insertPhotoEntry($entry, $albumEntry->getEditLink()->getHref());
} catch(\Exception $e){
    p($client);
    throw $e;
}
