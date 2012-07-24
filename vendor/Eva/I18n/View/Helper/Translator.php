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

namespace Eva\I18n\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\I18n\Exception;

/**
 * View helper for translating messages.
 *
 * @category   Eva
 * @package    Eva_I18n
 * @subpackage View
 */
class Translator extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Translator instance.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Set translator.
     *
     * @param  Translator $translator
     * @return Translate
     */
    public function setTranslator(\Zend\I18n\Translator\Translator $translator)
    {
        $this->translator = $translator;
        return $this;
    }

    public function getTranslator()
    {
        if($this->translator) {
            return $this->translator;
        }
    }

    /**
     * Translate a message.
     *
     * @param  string $message
     * @param  string $textDomain
     * @param  string $locale
     * @return string
     * @throws Exception\RuntimeException
     */
    public function __invoke($message, $textDomain = 'default', $locale = null)
    {
        if ($this->translator === null) {
            return $message;
        }

        return $this->translator->translate($message, $textDomain, $locale);
    }
}
