<?php

namespace BjyProfiler\Db\Adapter\Driver\Pdo;

use BjyProfiler\Db\Profiler\Profiler;
use Zend\Db\Adapter\Driver\Pdo\Statement;

class ProfilingStatement extends Statement
{
    protected $profiler;

    public function execute($parameters = null)
    {
        if ($parameters === null) {
            if ($this->parameterContainer != null) {
                $saveParams = (array) $this->parameterContainer->getNamedArray();
            } else {
                $saveParams = array();
            }
        } else {
            $saveParams = $parameters;
        }

        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        $queryId = $this->getProfiler()->startQuery($this->getSql(), $saveParams, $stack);
        $result = parent::execute($parameters);
        $this->getProfiler()->endQuery($queryId);

        return $result;
    }

    public function setProfiler(Profiler $p)
    {
        $this->profiler = $p;
        return $this;
    }

    public function getProfiler()
    {
        return $this->profiler;
    }
}
