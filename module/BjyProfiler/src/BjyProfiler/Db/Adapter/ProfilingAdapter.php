<?php

namespace BjyProfiler\Db\Adapter;

use BjyProfiler\Db\Profiler\Profiler;
use Zend\Db\Adapter\Adapter;

class ProfilingAdapter extends Adapter
{
    protected $profiler;

    public function setProfiler(Profiler $p)
    {
        $this->profiler = $p;
        return $this;
    }

    public function getProfiler()
    {
        return $this->profiler;
    }


    public function injectProfilingStatementPrototype()
    {
        $profiler = $this->getProfiler();
        if (!$profiler instanceof Profiler) {
            throw new \InvalidArgumentException('No profiler attached!');
        }

        $driver = $this->getDriver();
        if (method_exists($driver, 'registerStatementPrototype')) {
            $driverName = get_class($driver);
            switch ($driverName) {
                case 'Zend\Db\Adapter\Driver\Mysqli\Mysqli':
                    $statementPrototype = new Driver\Mysqli\ProfilingStatement();
                    break;
                case 'Zend\Db\Adapter\Driver\Sqlsrv\Sqlsrv':
                    $statementPrototype = new Driver\Sqlsrv\ProfilingStatement();
                    break;
                case 'Zend\Db\Adapter\Driver\Pgsql\Pgsql':
                    $statementPrototype = new Driver\Pgsql\ProfilingStatement();
                    break;
                case 'Zend\Db\Adapter\Driver\Pdo\Pdo':
                default:
                    $statementPrototype = new Driver\Pdo\ProfilingStatement();
            }

            if(isset($statementPrototype)) {
                $statementPrototype->setProfiler($this->getProfiler());
                $driver->registerStatementPrototype($statementPrototype);
            }

        }
    }
}

