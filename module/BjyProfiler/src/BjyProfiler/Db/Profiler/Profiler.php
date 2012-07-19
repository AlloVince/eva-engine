<?php

namespace BjyProfiler\Db\Profiler;

class Profiler
{

    /**
     * Logical OR these together to get a proper query type filter
     */
    const CONNECT       = 1;
    const QUERY         = 2;
    const INSERT        = 4;
    const UPDATE        = 8;
    const DELETE        = 16;
    const SELECT        = 32;
    const TRANSACTION   = 64;

    /**
     * @var array
     */
    protected $profiles = array();

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var int
     */
    protected $filterTypes;

    public function __construct($enabled = true)
    {
        $this->enabled = $enabled;
        $this->filterTypes = 127;
    }

    public function setFilterQueryType($queryTypes = null)
    {
        $this->filterTypes = $queryTypes;
        return $this;
    }

    public function getFilterQueryType()
    {
        return $this->filterTypes;
    }

    public function startQuery($sql, $parameters = null, $stack = array())
    {
        if (!$this->enabled) {
            return null;
        }

        // try to detect the query type
        switch (strtolower(substr(ltrim($sql), 0, 6))) {
            case 'select':
                $queryType = static::SELECT;
                break;
            case 'insert':
                $queryType = static::INSERT;
                break;
            case 'update':
                $queryType = static::UPDATE;
                break;
            case 'delete':
                $queryType = static::DELETE;
                break;
            default:
                $queryType = static::QUERY;
                break;
        }

        $profile = new Query($sql, $queryType, $parameters, $stack);
        $this->profiles[] = $profile;
        $profile->start();

        end($this->profiles);
        return key($this->profiles);
    }

    public function endQuery($queryId)
    {
        if (!$this->enabled) {
            return false;
        }

        $queryProfile = $this->profiles[$queryId];
        $queryProfile->end();

        return true;
    }

    public function getQueryProfiles($queryTypes = null)
    {
        $profiles = array();

        if (count($this->profiles)) {
            foreach ($this->profiles as $id => $profile) {
                if ($queryTypes === null) {
                    $queryTypes = $this->filterTypes;
                }

                if ($profile->getQueryType() & $queryTypes) {
                    $profiles[$id] = $profile;
                }
            }
        }

        return $profiles;
    }

}
