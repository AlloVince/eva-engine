<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Db
 */

namespace Eva\Db\Metadata\Source;

use Zend\Db\Metadata\MetadataInterface;
use Zend\Db\Adapter\Adapter;
use Eva\Db\Metadata\Object;

/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage Metadata
 */
class MysqlMetadata extends \Zend\Db\Metadata\Source\MysqlMetadata
{
    protected function loadColumnData($table, $schema)
    {
        if (isset($this->data['columns'][$schema][$table])) {
            return;
        }
        $this->prepareDataHierarchy('columns', $schema, $table);
        $p = $this->adapter->getPlatform();

        $isColumns = array(
            array('C','ORDINAL_POSITION'),
            array('C','COLUMN_DEFAULT'),
            array('C','IS_NULLABLE'),
            array('C','DATA_TYPE'),
            array('C','CHARACTER_MAXIMUM_LENGTH'),
            array('C','CHARACTER_OCTET_LENGTH'),
            array('C','NUMERIC_PRECISION'),
            array('C','NUMERIC_SCALE'),
            array('C','COLUMN_NAME'),
            array('C','COLUMN_TYPE'),
        );

        array_walk($isColumns, function (&$c) use ($p) { $c = $p->quoteIdentifierChain($c); });

        $sql = 'SELECT ' . implode(', ', $isColumns)
             . ' FROM ' . $p->quoteIdentifierChain(array('INFORMATION_SCHEMA','TABLES')) . 'T'
             . ' INNER JOIN ' . $p->quoteIdentifierChain(array('INFORMATION_SCHEMA','COLUMNS')) . 'C'
             . ' ON ' . $p->quoteIdentifierChain(array('T','TABLE_SCHEMA'))
             . '  = ' . $p->quoteIdentifierChain(array('C','TABLE_SCHEMA'))
             . ' AND ' . $p->quoteIdentifierChain(array('T','TABLE_NAME'))
             . '  = ' . $p->quoteIdentifierChain(array('C','TABLE_NAME'))
             . ' WHERE ' . $p->quoteIdentifierChain(array('T','TABLE_TYPE'))
             . ' IN (' . $p->quoteValueList(array('BASE TABLE', 'VIEW')) . ')'
             . ' AND ' . $p->quoteIdentifierChain(array('T','TABLE_NAME'))
             . '  = ' . $p->quoteValue($table);

        if ($schema != self::DEFAULT_SCHEMA) {
            $sql .= ' AND ' . $p->quoteIdentifierChain(array('T','TABLE_SCHEMA'))
                  . ' = ' . $p->quoteValue($schema);
        } else {
            $sql .= ' AND ' . $p->quoteIdentifierChain(array('T','TABLE_SCHEMA'))
                  . ' != ' . $p->quoteValue('INFORMATION_SCHEMA');
        }

        $results = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $columns = array();
        foreach ($results->toArray() as $row) {
            $columns[$row['COLUMN_NAME']] = array(
                'ordinal_position'          => $row['ORDINAL_POSITION'],
                'column_default'            => $row['COLUMN_DEFAULT'],
                'is_nullable'               => ('YES' == $row['IS_NULLABLE']),
                'data_type'                 => $row['DATA_TYPE'],
                'character_maximum_length'  => $row['CHARACTER_MAXIMUM_LENGTH'],
                'character_octet_length'    => $row['CHARACTER_OCTET_LENGTH'],
                'numeric_precision'         => $row['NUMERIC_PRECISION'],
                'numeric_scale'             => $row['NUMERIC_SCALE'],
                'numeric_unsigned'          => (false !== strpos($row['COLUMN_TYPE'], 'unsigned')),
                'column_type'               => $row['COLUMN_TYPE'],
                'erratas'                   => array(),
            );
        }

        $this->data['columns'][$schema][$table] = $columns;
    }

    /**
     * Get column
     *
     * @param  string $columnName
     * @param  string $table
     * @param  string $schema
     * @return Object\ColumnObject
     */
    public function getColumn($columnName, $table, $schema = null)
    {
        if ($schema === null) {
            $schema = $this->defaultSchema;
        }

        $this->loadColumnData($table, $schema);

        if (!isset($this->data['columns'][$schema][$table][$columnName])) {
            throw new \Exception('A column by that name was not found.');
        }

        $info = $this->data['columns'][$schema][$table][$columnName];

        $column = new Object\ColumnObject($columnName, $table, $schema);
        $props = array(
            'ordinal_position', 'column_default', 'is_nullable',
            'data_type', 'character_maximum_length', 'character_octet_length',
            'numeric_precision', 'numeric_scale', 'numeric_unsigned',
            'erratas', 'column_type'
        );
        foreach ($props as $prop) {
            if (isset($info[$prop])) {
                $column->{'set' . str_replace('_', '', $prop)}($info[$prop]);
            }
        }

        $column->setOrdinalPosition($info['ordinal_position']);
        $column->setColumnDefault($info['column_default']);
        $column->setIsNullable($info['is_nullable']);
        $column->setDataType($info['data_type']);
        $column->setCharacterMaximumLength($info['character_maximum_length']);
        $column->setCharacterOctetLength($info['character_octet_length']);
        $column->setNumericPrecision($info['numeric_precision']);
        $column->setNumericScale($info['numeric_scale']);
        $column->setNumericUnsigned($info['numeric_unsigned']);
        $column->setErratas($info['erratas']);

        return $column;
    }
}
