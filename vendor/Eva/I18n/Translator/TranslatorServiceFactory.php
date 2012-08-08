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


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Translator.
 *
 * @category   Eva
 * @package    Eva_I18n
 * @subpackage Translator
 */
class TranslatorServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Configure the translator
        //$config = $serviceLocator->get('Configuration');
        //if(!isset($config['translator']) || !is_array($config['translator'])){
        //    $config['translator'] = array();
        //}

        //Use ModuleManager here to get changed config
        $mm = $serviceLocator->get('ModuleManager');
        $config = $mm->getEvent()->getParam('configListener')->getMergedConfig()->toArray();
        $translator = Translator::factory($config['translator']);

        //NOTE: Zf2 i18n require Locale class installed. Here could invoid no install Locale
        if(isset($config['translator']['locale'])){
            $translator->setFallbackLocale($config['translator']['locale']);
        }
        return $translator;
    }
}
