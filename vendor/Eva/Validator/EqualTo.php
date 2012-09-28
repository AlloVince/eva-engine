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
class EqualTo extends \Zend\Validator\AbstractValidator
{
    const NOT_EQUAL_TO           = 'notEqualTo';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_EQUAL_TO => "The input is not match",
    );

    protected $data;

    protected $field;

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Returns true if and only if $value is greater than min option
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->setValue($value);

        $field = $this->getField();
        $equalTo = isset($this->data[$field]) ? $this->data[$field] : '';
        if($value === $equalTo){
            return true;
        }
        $this->error(self::NOT_EQUAL_TO);
        return false;
    }

    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if(isset($options['data'])){
            $this->setData($options['data']);
        }

        if (!array_key_exists('field', $options)) {
            throw new Exception\InvalidArgumentException("Missing option 'field'");
        }

        $this->setField($options['field']);

        parent::__construct($options);
    }
}
