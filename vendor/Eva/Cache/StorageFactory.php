<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Cache
 * @subpackage Storage
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\Cache;


/**
 * @category   Zend
 * @package    Zend_Cache
 * @subpackage Storage
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class StorageFactory extends \Zend\Cache\StorageFactory
{
    protected static $defaultAdapterName;
    protected static $cacheAdapters = null;

    public static function factory($cfg)
    {
        $adapter = parent::factory($cfg);
        $adapterName    = $cfg['adapter'];
        if (is_array($cfg['adapter'])) {
            if (!isset($cfg['adapter']['name'])) {
                throw new Exception\InvalidArgumentException('Missing "adapter.name"');
            }
            $adapterName    = $cfg['adapter']['name'];
            self::$defaultAdapterName = $adapterName;
            self::$cacheAdapters[$adapterName] = $adapter;
        }

        return $adapter;
    }

    public static function getAdapter()
    {
        $adapterName = self::$defaultAdapterName;
        if(isset(self::$cacheAdapters[$adapterName])){
            return self::$cacheAdapters[$adapterName];
        }

        return false;
    }

}
