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

namespace Eva\Mvc\Controller;

/**
 * Eva Plugin manager for register custom plugins
 *
 * @category   Eva
 * @package    Eva_Mvc
 * @subpackage Controller
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class PluginManager extends \Zend\Mvc\Controller\PluginManager
{
    /**
     * Default set of plugins
     *
     * @var array
     */
    protected $invokableClasses = array(
        'flashmessenger' => 'Zend\Mvc\Controller\Plugin\FlashMessenger',
        'forward'        => 'Zend\Mvc\Controller\Plugin\Forward',
        'layout'         => 'Zend\Mvc\Controller\Plugin\Layout',
        'params'         => 'Zend\Mvc\Controller\Plugin\Params',
        'redirect'       => 'Zend\Mvc\Controller\Plugin\Redirect',
        'url'            => 'Zend\Mvc\Controller\Plugin\Url',
        'pagecapture'    => 'Eva\Mvc\Controller\Plugin\PageCapture',
    );
}
