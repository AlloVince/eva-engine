<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Authentication
 */

namespace Contacts\Storage;

/**
 * @category   Zend
 * @package    Zend_Authentication
 * @subpackage Storage
 */
interface StorageInterface
{
    public function saveContacts($contacts = array());

    public function loadContacts();
    
    public function removeContacts($email);
}
