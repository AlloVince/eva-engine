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

use Zend\I18n\View\Helper\AbstractTranslatorHelper;
use Zend\I18n\Exception;
use Eva\Locale\Data;

/**
 * View helper for translating messages.
 *
 * @category   Eva
 * @package    Eva_I18n
 * @subpackage View
 */
class Locale extends AbstractTranslatorHelper
{
    /**
     * Translator instance.
     *
     * @var Translator
     */
    protected $translator;

    //dataType: territory | language
    public function __invoke($dataType, $dataKey, $locale = null)
    {
        if(!$locale) {
            $locale = $this->translator->getLocale();
        }
        $list = Data::getList($locale, $dataType);
        if(isset($list[$dataKey])){
            return $list[$dataKey];
        }
    }
    
}
