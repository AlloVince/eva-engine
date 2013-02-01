<?php
require_once './autoloader.php';



use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;

$adapter = new Zend\Db\Adapter\Adapter(array(
    'driver' => 'pdo',
    'dsn' => 'mysql:dbname=eva;hostname=localhost',
    'username' => 'root',
    'password' => '582tsost',
    'driver_options' => array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    ),
));


// Configure the instance with constructor parameters...
$authAdapter = new AuthAdapter($adapter);
$authAdapter
->setTableName('eva_user_users')
->setIdentityColumn('userName')
->setCredentialColumn('password')
;

$authAdapter
    ->setIdentity('AlloVince')
    ->setCredential('$2y$14$WmZhcUFqblVqaU1ieUVqZOOKNVv3GZS8DmRLfBOkD53OvG6fvbBjy')
;



// instantiate the authentication service
$auth = new AuthenticationService();

// Attempt authentication, saving the result
$result = $auth->authenticate($authAdapter);

if (!$result->isValid()) {
    // Authentication failed; print the reasons why
    foreach ($result->getMessages() as $message) {
        echo "$message\n";
    }
} else {
    p($auth->getIdentity());
}


