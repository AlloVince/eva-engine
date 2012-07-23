BjyProfiler
===========
Provides Zend\Db adapters with extensions for database query profiling, as well as a profiler similar to ZF1's Zend\_Db\_Profiler.
I ported much of this code from ZF1's Zend_Db.

Configuration & Usage
---------------------
Following is a sample database configuration:

```php
<?php

$dbParams = array(
    'database'  => 'changeme',
    'username'  => 'changeme',
    'password'  => 'changeme',
    'hostname'  => 'localhost'
);

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => function ($sm) use ($dbParams) {
                $adapter = new BjyProfiler\Db\Adapter\ProfilingAdapter(array(
                    'driver'    => 'pdo',
                    'dsn'       => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
                    'database'  => $dbParams['database'],
                    'username'  => $dbParams['username'],
                    'password'  => $dbParams['password'],
                    'hostname'  => $dbParams['hostname'],
                ));

                $adapter->setProfiler(new BjyProfiler\Db\Profiler\Profiler);
                $adapter->injectProfilingStatementPrototype();
                return $adapter;
            },
        ),
    ),
);
```

After you've run a couple queries (or before, if you so choose), you can use the service locator to grab the adapter using whatever alias you provide (using Zend\Db\Adapter\Adapter is a good way to simply replace Zend\Db's adapter with my profiling adapter.

```php
$profiler = $sl->get('Zend\Db\Adapter\Adapter')->getProfiler();
$queryProfiles = $profiler->getQueryProfiles();
```
