<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_View
 */

namespace Eva\View\Helper;

use Zend\View\Helper\Placeholder;
use Zend\View\Exception;

/**
 * Helper for setting and retrieving title element for HTML head
 *
 * @package    Zend_View
 * @subpackage Helper
 */
class TextDelay extends \Zend\View\Helper\Placeholder\Container\AbstractStandalone
{
    /**
     * Registry key for placeholder
     * @var string
     */
     protected $_regKey = 'Eva_View_Helper_TextDelay';

     protected $_autoEscape = false;


    /**
     * Retrieve placeholder for title element and optionally set state
     *
     * @param  string $title
     * @param  string $setType
     * @return \Zend\View\Helper\HeadTitle
     */
    public function __invoke($textKey, $text = null)
    {
        if($textKey){
            $textKey = $this->_regKey . '_' . $textKey;
            $this->setContainer($this->getRegistry()->getContainer($textKey));
        }
        if ($text) {
            $this->getContainer()->set($text);
        }
        return $this;
    }

    /**
     * Turn helper into string
     *
     * @param  string|null $indent
     * @param  string|null $locale
     * @return string
     */
     public function toString()
     {
         $items = array();
         foreach ($this as $item) {
             $items[] = $item;
         }
         $output = '';
         $output .= implode('', $items);
         return $output; 
    }
}
