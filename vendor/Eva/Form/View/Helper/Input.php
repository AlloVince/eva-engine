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
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\Form\View\Helper;

use Zend\Form\FormInterface;
use Zend\Form\ElementInterface;

/**
 * View helper for rendering Form objects
 * 
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Input extends \Zend\Form\View\Helper\AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     * 
     * @param  ElementInterface $element 
     * @return string
     */
    public function __invoke(ElementInterface $element, array $options = array())
    {
        $defaultOptions = array(
            'type' => 'formInput',
            //'autoId' => true,
        );

        $options = array_merge($defaultOptions, $options);
        $elementType = $options['type'];
        unset($options['type']);

        //Support Subform
        if($subFormName = $element->getAttribute("data-subform-name")){
            $name = $element->getName();
            if($name){
                $element->setName($subFormName . '[' . $name . ']');
            }
        }

        if($options){
            //NOTE: clone element not effect to form original element
            //$element = clone $element;
            foreach($options as $key => $value){
                $element->setAttribute($key, $value);
            }
        }

        /*
        if(true === $options['autoId'] && !$elementId = $element->getAttribute('id')){
            $elementName = $element->getName();
            if($elementName){
                $elementId = str_replace(array('_','[',']'), '-', strtolower($elementName));
                $elementId = trim($elementId, '-');
                $element->setAttribute('id', $elementId);
            }
        }
        */

        $view = $this->getView();
        return $view->$elementType($element);
    }
}
