<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Config
 */

namespace Eva\Config;

/**
 * Provides a property based interface to an array.
 * The data are read-only unless $allowModifications is set to true
 * on construction.
 *
 * Implements Countable, Iterator and ArrayAccess
 * to facilitate easy access to the data.
 *
 * @category   Zend
 * @package    Zend_Config
 */
class Config extends \Zend\Config\Config
{
    public static function mergeArray()
    {
        if(0 === func_num_args()){
            return array();
        }

        if(1 === func_num_args()){
            return (array) func_get_arg(0);
        }

        $arrays = func_get_args();
        $baseConfig = new static(array_shift($arrays));
        foreach($arrays as $array){
            $config = new static($array);
            $baseConfig->merge($config);
        }
        return $baseConfig->toArray();
    }
}
