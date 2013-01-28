<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

namespace Core;

use Resque;
use Eva\Job\IndependentJobInterface;
use Eva\Job\RelatedJobInterface;
use Eva\Job\Exception;
use Eva\Api;


/**
 * Core Session
 *
 * @category   Core
 * @package    Core_Session
 */
class JobManager
{
    protected static $queue = 'default';

    public static function setQueue($queue)
    {
        return self::$queue = $queue;
    }

    public static function jobHandler($jobName, $args)
    {
        if(false === class_exists($jobName)){
            throw new Exception\BadFunctionCallException(sprintf('Job %s not exist', $jobName));
        }

        $config = Api::_()->getConfig();
        if(isset($config['queue']['enable']) && $config['queue']['enable'] && class_exists(Resque)){
            Resque::enqueue(self::$queue, $jobName, $args, true);
        } else {
            $job = new $jobName();
            if($job instanceof IndependentJobInterface){
                $job->args = $args;
                $job->perform();
            } else {
                throw new Exception\BadFunctionCallException(sprintf('Job %s is not a Independent Job, php-resque installation required', $jobName));
            }
        }
    }
}
