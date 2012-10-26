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
* Render View Partial Cross Module
* 
* @category   Eva
* @package    Eva_View
* @subpackage Helper
* @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
* @license    http://framework.zend.com/license/new-bsd     New BSD License
*/
class SubText extends AbstractHelper
{
    public function __invoke($text, $length = 100, $tagFilter = true)
    {
        if(true === $tagFilter){
            $text = strip_tags($text);
        }

        $text = $this->subString($text, $length);

        return $text;
    }

    protected function subString($str, $length, $encoding = "UTF-8")
	{
		mb_internal_encoding($encoding);
		$len = mb_strlen($str);
		if($len > $length){
			$str = mb_substr($str, 0, $length);
		}
		return $str . (($len > $length) ? '...' : '');
	}

}
