<?php
require_once './autoloader.php';

class MyDbTable extends Zend\Db\TableGateway\TableGateway
{
}

$adapter = new Zend\Db\Adapter\Adapter(array(
    'driver' => 'pdo',
    'dsn' => 'mysql:dbname=eva;hostname=localhost',
    'username' => 'root',
    'password' => 'password',
    'driver_options' => array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    ),
));


$myDbTable = new MyDbTable('MyDbTable', $adapter);

$select = $myDbTable->getSql()->select();
$where = $select->where;
$where->lessThan('id', 10);
$where->greaterThan('id', 5);
$select->order('id DESC')->limit(10);
$myDbTable->selectWith($select);


$select = $myDbTable->getSql()->select();
$select->where('id > 1')->order('id DESC')->limit(10);
$resultSet = $myDbTable->selectWith($select);
$result = $resultSet->toArray();


$select = $myDbTable->getSql()->select();
$select->where(function($where){
    $where->lessThan('id', 10);
    $where->greaterThan('id', 5);
    return $where;
})->order('id DESC')->limit(10);
$resultSet = $myDbTable->selectWith($select);
$result = $resultSet->toArray();


$select = $myDbTable->getSql()->select();
$select->where(
    array('id > 30')
)->where(
    array('id < 10')
);
$resultSet = $myDbTable->selectWith($select);
$result = $resultSet->toArray();

$select = $myDbTable->getSql()->select();
$select->where(
    array('id > 30')
)->where(
    array('id < 10'), \Zend\Db\Sql\Where::OP_OR
);
$resultSet = $myDbTable->selectWith($select);
$result = $resultSet->toArray();

$select = $myDbTable->getSql()->select();
$where = $select->where;
$where->lessThan('id', 10);
$where->or;
$where->greaterThan('id', 30);
$resultSet = $myDbTable->selectWith($select);
$result = $resultSet->toArray();

$select = $myDbTable->getSql()->select();
$select->where(function($where){

    $subWhereForId = clone $where;
    $subWhereForTitle = clone $where;

    $subWhereForId->lessThan('id', 10);
    $subWhereForId->or;
    $subWhereForId->greaterThan('id', 20);

    $where->addPredicate($subWhereForId);

    $subWhereForTitle->equalTo('title', 'a');
    $subWhereForTitle->or;
    $subWhereForTitle->equalTo('title', 'b');
    $where->addPredicate($subWhereForTitle);

    return $where;
});
$resultSet = $myDbTable->selectWith($select);
$result = $resultSet->toArray();


$select = $myDbTable->getSql()->select();
$select->where(array(
    'id' => new Zend\Db\Sql\Expression('NOW()')
));
$resultSet = $myDbTable->selectWith($select);
$result = $resultSet->toArray();


$select = $myDbTable->getSql()->select();
$idArray = array('2', '1');
$select->where(array(
    'id' => $idArray
));
$order = sprintf('FIELD(id, %s)', implode(',', array_fill(0, count($idArray), Zend\Db\Sql\Expression::PLACEHOLDER)));
$select->order(array(new Zend\Db\Sql\Expression($order, $idArray)));
$resultSet = $myDbTable->selectWith($select);
$result = $resultSet->toArray();
