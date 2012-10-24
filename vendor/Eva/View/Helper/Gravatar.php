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
 * Gravatar
 * 
 * @category   Eva
 * @package    Eva_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Gravatar extends \Zend\View\Helper\AbstractHelper
{
    public function __invoke($email, $size = 60, $default = '')
    {
        $gravUrl = "http://www.gravatar.com/avatar.php?" .
            "gravatar_id=" . md5( strtolower($email) ) .
            "&size=" . $size;
        return $gravUrl;
    }
}
