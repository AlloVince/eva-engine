<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Eva\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\View\Exception;

/**
* Datetime helper
* 
* @category   Eva
* @package    Eva_View
* @subpackage Helper
* @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
* @license    http://framework.zend.com/license/new-bsd     New BSD License
*/
class Datetime extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
    * @var Default timezone
    */
    protected $timezone;

    /**
    * @var Default datetime format
    */
    protected $format;

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    public function getTimezone()
    {
        if($this->timezone){
            return $this->timezone;
        }

        $config = $this->serviceLocator->getServiceLocator()->get('Configuration');
        if(isset($config['datetime']['timezone'])){
            return $this->timezone = $config['datetime']['timezone'];
        }
        return $this->timezone = 0;
    }

    public function setTimezone($timezone)
    {
        $this->timezone = (int) $timezone;
    }

    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    public function getFormat()
    {
        if($this->format){
            return $this->format;
        }

        $config = $this->serviceLocator->getServiceLocator()->get('Configuration');
        if(isset($config['datetime']['format'])){
            return $this->format = $config['datetime']['format'];
        }
        return $this->timezone = 'F j, Y, g:i a';
    }



    /**
    * Set the service locator.
    *
    * @param ServiceLocatorInterface $serviceLocator
    * @return AbstractHelper
    */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function __construct()
    {
        date_default_timezone_set('UTC');
    }


    public function __invoke($time = '', $timezone = null, $format = '')
    {
        if (0 == func_num_args()) {
            return $this;
        }

        $timezone = $timezone !== null ? $timezone : $this->getTimezone();
        $format = $format ? $format : $this->getFormat();

        $time = $time ? $time : time();
        $time = is_numeric($time) ? $time : strtotime($time);
        $time = $time + $timezone * 3600;

        return date($format, $time);
    }


    /**
     * Tranform input time to timeStamp
     *
     * @param string $time
     *
     * @access public
     *
     * @return Int  timeStamp
     */
    public function timeStamp($time = '')
    {
        return $time ? strtotime($time) : time();
    }

    /**
     * Tranform input time to time string which can be parsed by javascript
     *
     * @param string $time
     *
     * @access public
     *
     * @return string javascript parse-able string
     */
    public function jsTime($time = '' , $timezone = null)
    {
        if (!$time) {
            return;
        }

        $timezone = $timezone ? $timezone : $this->getTimezone();
    
        $time = $time ? strtotime($time) : time();
        $time = $time + $timezone * 3600;
        $prefix = $timezone < 0 ? '-' : '+';
    
        $zone = str_pad(str_pad(abs($timezone), 2, 0, STR_PAD_LEFT), 4, 0);
        return date('D M j H:i:s', $time) . ' UTC' . $prefix . $zone . ' ' . date('Y', $time);
    }


    /**
     * Tranform input time to iso time
     *
     * @param string $time
     * @param int $timezone
     *
     * @access public
     *
     * @return string time string
     */
    public function isoTime($time = null, $timezone = null)
    {
        $timezone = $timezone ? $timezone : $this->getTimezone();
		$time = $time ? strtotime($time) : time();
		return $time = date('c', $time);
    }
}
