<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Db
 */

namespace Eva\Db\Metadata;

use Zend\Db\Adapter\Adapter,
    Zend\Db\Metadata\MetadataInterface,
    Zend\Db\Adapter\Driver;

/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage Metadata
 */
class Metadata extends \Zend\Db\Metadata\Metadata
{
    /**
     * Create source from adapter
     * 
     * @param  Adapter $adapter
     * @return Source\InformationSchemaMetadata 
     */
    protected function createSourceFromAdapter(Adapter $adapter)
    {
        switch ($adapter->getPlatform()->getName()) {
            case 'MySQL':
                return new Source\MysqlMetadata($adapter);
            case 'SQLServer':
                return new Source\SqlServerMetadata($adapter);
            case 'SQLite':
                return new Source\SqliteMetadata($adapter);
            case 'PostgreSQL':
                return new Source\PostgresqlMetadata($adapter);
        }

        throw new \Exception('cannot create source from adapter');
    }
}
