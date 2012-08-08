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

namespace Eva\I18n\Translator;

use Zend\I18n\Exception;

/**
 * Plugin manager implementation for translation loaders.
 *
 * Enforces that filters retrieved are either callbacks or instances of
 * Loader\LoaderInterface. Additionally, it registers a number of default
 * loaders.
 *
 * @category   Eva
 * @package    Eva_I18n
 * @subpackage Translator
 */
class LoaderPluginManager extends \Zend\I18n\Translator\LoaderPluginManager
{
    /**
     * Default set of loaders
     *
     * @var array
     */
    protected $invokableClasses = array(
        'phparray' => 'Zend\I18n\Translator\Loader\PhpArray',
        'gettext'  => 'Zend\I18n\Translator\Loader\Gettext',
        'csv'      => 'Eva\I18n\Translator\Loader\Csv',
    );
}
