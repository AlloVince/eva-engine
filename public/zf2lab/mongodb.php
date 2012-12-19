<?php
require_once './autoloader.php';

$connection = new MongoClient(); // connects to localhost:27017
$db = $connection->testDb;
$collection = $db->testCollection;

/*
$doc = array(
    "name" => "MongoDB",
    "type" => "database",
    "count" => 1,
    "info" => (object)array( "x" => 203, "y" => 102),
    "versions" => array("0.9.7", "0.9.8", "0.9.9")
);
$collection->insert( $doc );
*/

$count = $collection->count();
$item = $collection->findOne();
p($count);
p($item);

$cursor = $collection->find();
foreach ( $cursor as $id => $value )
{
    echo "$id: ";
    var_dump( $value );
}

