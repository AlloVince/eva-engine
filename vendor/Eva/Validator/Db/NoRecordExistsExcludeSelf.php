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
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\Validator\Db;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\Adapter\Driver\DriverInterface as DbDriverInterface;
use Zend\Db\Sql\Select as DbSelect;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

/**
 * Confirms a record does not exist in a table.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class NoRecordExistsExcludeSelf extends \Zend\Validator\Db\AbstractDb
{

    /**
     * Gets the select object to be used by the validator.
     * If no select object was supplied to the constructor,
     * then it will auto-generate one from the given table,
     * schema, field, and adapter options.
     *
     * @return DbSelect The Select object which will be used
     */
    public function getSelect()
    {
        if (null === $this->select) {
            $adapter  = $this->getAdapter();
            $driver   = $adapter->getDriver();
            $platform = $adapter->getPlatform();

            /**
             * Build select object
             */
            $select = new DbSelect();
            $select->from($this->table, $this->schema)->columns(
                array($this->field)
            );

            // Support both named and positional parameters
            if (DbDriverInterface::PARAMETERIZATION_NAMED == $driver->getPrepareType()) {
                $select->where(
                    $platform->quoteIdentifier($this->field, true) . ' = :value'
                );
            } else {
                $select->where(
                    $platform->quoteIdentifier($this->field, true) . ' = ?'
                );
            }

            if ($this->exclude !== null) {

                //Fixed zend bug here
                if (is_array($this->exclude)) {
                    if (DbDriverInterface::PARAMETERIZATION_NAMED == $driver->getPrepareType()) {
                        $select->where(
                            $platform->quoteIdentifier($this->exclude['field'], true) .
                            ' != :excludevalue'
                        );
                    } else {
                        $select->where(
                            $platform->quoteIdentifier($this->exclude['field'], true) .
                            ' != ?', $this->exclude['value']
                        );
                    }
                } else {
                    $select->where($this->exclude);
                }
            }

            $this->select = $select;
        }

        return $this->select;
    }

    /**
     * Run query and returns matches, or null if no matches are found.
     *
     * @param  string $value
     * @return array when matches are found.
     */
    protected function query($value)
    {
        $adapter  = $this->getAdapter();
        $statement = $adapter->createStatement();
        $this->getSelect()->prepareStatement($adapter, $statement);

        return $statement->execute(array(
            'value' => $value,
            'excludevalue' => $this->exclude['value'],
        ))->current();
    }

    public function isValid($value)
    {
        /*
         * Check for an adapter being defined. If not, throw an exception.
         */
        if (null === $this->adapter) {
            throw new Exception\RuntimeException('No database adapter present');
        }

        $valid = true;
        $this->setValue($value);

        if(!$this->exclude['value']) {
            throw new Exception\RuntimeException('No exclude value set');
        }
        if(!$this->exclude['field']){
            $this->exclude['field'] = $this->getField();
        }
        if(!$this->exclude['field']) {
            throw new Exception\RuntimeException('No exclude field set');
        }

        $result = $this->query($value);
        if ($result) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }

        return $valid;
    }
}
