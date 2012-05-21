<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\Mvc\View;


use Traversable;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Zend\View\HelperBroker as ViewHelperBroker;
use Zend\View\HelperLoader as ViewHelperLoader;
use Zend\View\Renderer\PhpRenderer as ViewPhpRenderer;
use Zend\View\Resolver as ViewResolver;
use Zend\View\Strategy\PhpRendererStrategy;
use Zend\View\View;

class ModuleViewManager extends \Zend\Mvc\View\ViewManager
{
    public function getResolver()
    {
        if ($this->resolver) {
            return $this->resolver;
        }

        $map = array();
        if (isset($this->config['view_manager']) && isset($this->config['view_manager']['template_map'])) {
            $map = $this->config['view_manager']['template_map'];
        }
        $templateMapResolver = new ViewResolver\TemplateMapResolver($map);

        $stack = array();
        if (isset($this->config['view_manager']) && isset($this->config['view_manager']['template_path_stack'])) {
            $stack = $this->config['view_manager']['template_path_stack'];
            if ($stack instanceof Traversable) {
                $stack = ArrayUtils::iteratorToArray($stack);
            }
		}
        $templatePathStack = new ViewResolver\TemplatePathStack();
        $templatePathStack->addPaths($stack);

        $this->resolver = new ViewResolver\AggregateResolver();
        $this->resolver->attach($templateMapResolver);
        $this->resolver->attach($templatePathStack);

        $this->services->setService('ViewTemplateMapResolver', $templateMapResolver);
        $this->services->setService('ViewTemplatePathStack', $templatePathStack);
        $this->services->setService('ViewResolver', $this->resolver);

        $this->services->setAlias('Zend\View\Resolver\TemplateMapResolver', 'ViewTemplateMapResolver');
        $this->services->setAlias('Zend\View\Resolver\TemplatePathStack', 'ViewTemplatePathStack');
        $this->services->setAlias('Zend\View\Resolver\AggregateResolver', 'ViewResolver');
        $this->services->setAlias('Zend\View\Resolver\ResolverInterface', 'ViewResolver');

        return $this->resolver;
	}


    /**
     * Instantiates and configures the default MVC rendering strategy
     * 
     * @return DefaultRenderingStrategy
     */
    public function getMvcRenderingStrategy()
    {
        if ($this->mvcRenderingStrategy) {
            return $this->mvcRenderingStrategy;
        }

        $this->mvcRenderingStrategy = new \Eva\Mvc\View\DefaultModuleRenderingStrategy($this->getView());
        $this->mvcRenderingStrategy->setLayoutTemplate($this->getLayoutTemplate());

        $this->services->setService('DefaultRenderingStrategy', $this->mvcRenderingStrategy);
        $this->services->setAlias('Eva\Mvc\View\DefaultModuleRenderingStrategy', 'DefaultRenderingStrategy');

        return $this->mvcRenderingStrategy;
    }
}
