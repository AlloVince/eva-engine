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

use Zend\View\Exception;

/**
 * Render View Partial Cross Module
 * 
 * @category   Eva
 * @package    Eva_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Thumb extends \Zend\View\Helper\AbstractHelper
{

    public function __invoke($url, array $args = array())
    {
        if(!$args || !$url){
            return $url;
        }

        sort($args);
        $url = explode('/', $url);
        $fileName = array_pop($url);
        $nameArray = explode('.', $fileName);
        $nameExt = array_pop($nameArray);
        $nameFinal = array_pop($nameArray);
        $nameFinal .= ',' . implode(',', $args);
        array_push($nameArray, $nameFinal, $nameExt);
        $fileName = implode('.', $nameArray);

        array_push($url, $fileName);
        return implode('/', $url);
    }
}
