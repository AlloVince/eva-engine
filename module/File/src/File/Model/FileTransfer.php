<?php

namespace File\Model;

use Eva\Api;
use Zend\File\Transfer\Adapter;
use Zend\File\Transfer\Exception;

class FileTransfer extends \Zend\File\Transfer\Transfer
{
    public function setAdapter($adapter, $direction = false)
    {
        $direction = (integer) $direction;
        $this->adapter[$direction] = $adapter;
        if (!$this->adapter[$direction] instanceof Adapter\AbstractAdapter) {
            throw new Exception\InvalidArgumentException(
                'Adapter ' . $adapter . ' does not extend Zend\File\Transfer\Adapter\AbstractAdapter'
            );
        }

        return $this;
    }
}
