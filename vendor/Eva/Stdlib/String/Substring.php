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

 namespace Eva\Stdlib\String;

 /**
 * Unique Hash
 * From : http://blog.kevburnsjr.com/php-unique-hash
 * 
 * @category  Eva
 * @package   Eva_Stdlib
 */
 class Substring
 {
	/**
	 * Cutting string without word break
	 *
	 * @access public
	 * @param string $str string
	 * @param int $length allowed length int
	 *
	 * @return string
	 */
	public static function subStringWithWrap($str = '', $length = 1)
	{
		$len = strlen($str);

		if($len <= $length) {
			return $str;
		}

		for($i = $length; $i > -1; $i--){
			if($str{$i} == ' ')    {
				return substr($str, 0, $i) . ' ...';
			}
		}

        return substr($str, 0, $length) . ' ...';
    }

    public static function subCNStringWithWrap($str, $length, $encoding = "UTF-8")
	{
		mb_internal_encoding($encoding);
		
		$len = mb_strlen($str);
		
		if($len > $length){
			$str = mb_substr($str,0,$length);
		}
		
		return $str . (($len > $length) ? '...' : '');
	}
 }
