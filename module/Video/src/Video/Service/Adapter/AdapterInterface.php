<?php

namespace Video\Service\Adapter;

/**
 * @category   Zend
 * @package    Zend_Authentication
 * @subpackage Adapter
 */
interface AdapterInterface
{
    public function isValid();

    public function getSwfUrl();

    public function getRemoteId();
}
