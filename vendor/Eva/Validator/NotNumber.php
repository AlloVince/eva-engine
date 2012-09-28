<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Validator
 */

namespace Eva\Validator;

use Eva\Api,
    Zend\Validator\Exception,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class for Database record validation
 *
 * @category   Zend
 * @package    Zend_Validate
 */
class NotNumber extends \Zend\Validator\AbstractValidator
{
    const IS_NUMBER      = 'isNumber';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::IS_NUMBER => "The input is not allow all numbers",
    );

    /**
     * Returns true if and only if $value is greater than min option
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->setValue($value);

        if(is_numeric($value)){
            $this->error(self::IS_NUMBER);
            return false;
        }
        return true;
    }
}
