<?php
require_once './autoloader.php';

$adapter = new Zend\Db\Adapter\Adapter(array(
    'driver' => 'pdo',
    'dsn' => 'mysql:dbname=db;hostname=localhost',
    'username' => 'root',
    'password' => 'password',
    'driver_options' => array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    ),
));

$adapter->getDriver()->getConnection()->beginTransaction();
