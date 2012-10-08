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

namespace Eva\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Handler cookie
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Controller\Plugin
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Cookie extends AbstractPlugin
{
    protected $crypt = false;

    public function crypt($crypt = true)
    {
        $this->crypt = (boolean) $crypt;
        return $this;
    }

	protected function encode($value = null)
	{
		if(!$value){
			return '';
		}
		return base64_encode(gzcompress(serialize($value)));
    }

	protected function decode($value = null)
	{
		if(!$value) {
			return '';
		}

		$value = base64_decode($value);
		if(false == $value) {
			return '';
		}

		$value = gzuncompress($value);
		if(false == $value) {
			return '';
		}

		$value = unserialize($value);
		if(false == $value) {
			return '';
		}

		return $value;
	}

	/**
	 * Set System cookies
	 * Save input data into cookies
	 *
	 * @param string $key   cookie name to be set
	 * @param mixed $value   this param can be any data type
	 * @param int   $expire  expire seconds
	 *
     * @access public
     *
     * @return boolean set cookie result
     */
     public function write($key, $value,$expire = 86400, $path = '/')
     {
         $expire = time() + $expire;
         $value = true === $this->crypt ? $this->encode($value) : $value;
         return setrawcookie($key, $value, $expire, "/");
     }

     /**
     * Get System cookie
     *
     * @param string $key  cookie name to be get
     *
     * @access public
     *
     * @return mixed cookie data
     */
     public function read($key = null)
     {
         if($key) {
             if(!isset($_COOKIE[$key]) || !$_COOKIE[$key]){
                 return '';
             }

             return $this->crypt ? $this->decode($_COOKIE[$key]) : $_COOKIE[$key];
         }

         $res = array();
         foreach($_COOKIE as $key => $value) {
             if (!isset($_COOKIE[$key]) && $_COOKIE[$key]) {
                 continue;
             }
             $res[$key] = $this->crypt ? $this->decode($value) : $value;
         }
         return $res;
     }

	/**
	 * Unset System cookies
	 *
	 * @param string $key   cookie name to be unset
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function clear($key)
	{
        setrawcookie($key, null, -1, '/');
		unset($_COOKIE[$key]);
        return $this;
	}

 }
