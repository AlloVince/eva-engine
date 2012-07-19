<?php

namespace BjyProfiler\Db\Profiler;

use BjyProfiler\Db\Profiler\Profiler;

class Query
{
    protected $sql = '';
    protected $queryType = 0;
    protected $startTime = null;
    protected $endTime = null;
    protected $parameters = null;
    protected $callStack = array();

    public function __construct($sql, $queryType, $parameters = null, $stack = array())
    {
        $this->sql = $sql;
        $this->queryType = $queryType;
        $this->parameters = $parameters;
        $this->callStack = $stack;
    }

    public function start()
    {
        $this->startTime = microtime(true);
        return $this;
    }

    public function end()
    {
        $this->endTime = microtime(true);
        return $this;
    }

    public function hasEnded()
    {
        return ($this->endTime !== null);
    }

    public function getElapsedTime()
    {
        if (!$this->hasEnded()) {
            return false;
        }
        return $this->endTime - $this->startTime;
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function getQueryType()
    {
        return $this->queryType;
    }

    public function toArray()
    {
        switch ($this->queryType) {
            case Profiler::SELECT:
                $type = 'SELECT';
                break;
            case Profiler::INSERT:
                $type = 'INSERT';
                break;
            case Profiler::UPDATE:
                $type = 'UPDATE';
                break;
            case Profiler::DELETE:
                $type = 'DELETE';
                break;
            case Profiler::QUERY:
                $type = 'OTHER';
                break;
            case Profiler::CONNECT:
                $type = 'CONNECT';
                break;
        }

        return array(
            'type'    => $type,
            'sql'     => $this->sql,
            'start'   => $this->startTime,
            'end'     => $this->endTime,
            'elapsed' => $this->getElapsedTime(),
            'parameters' => $this->parameters,
            'stack'   => $this->callStack
        );
    }
}
