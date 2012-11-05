<?php

namespace Contacts\Import;

/**
 * @category   Zend
 * @package    Zend_Authentication
 * @subpackage Adapter
 */
interface AdapterInterface
{
    public function getRequestUrl();

    public function getContacts();
}
