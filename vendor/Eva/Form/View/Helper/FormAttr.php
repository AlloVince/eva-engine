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

namespace Eva\Form\View\Helper;

use Zend\Form\FormInterface;

/**
 * Form Attributes Generate helper
 * 
 * @category   Eva
 * @package    Eva_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class FormAttr extends \Zend\Form\View\Helper\AbstractHelper
{
    /**
     * Attributes valid for this tag (form)
     * 
     * @var array
     */
    protected $validTagAttributes = array(
        'accept-charset' => true,
        'action'         => true,
        'autocomplete'   => true,
        'enctype'        => true,
        'method'         => true,
        'name'           => true,
        'novalidate'     => true,
        'target'         => true,
    );

    /**
     * Invoke as function
     * 
     * @return Form
     */
    public function __invoke(FormInterface $form = null)
    {
        return $this->render($form);
    }

    /**
     * Generate an opening form tag
     * 
     * @param  null|FormInterface $form 
     * @return string
     */
    public function render(FormInterface $form = null)
    {
        $attributes = array(
            'action' => '',
            'method' => 'get',
        );

        if ($form instanceof FormInterface) {
            $formAttributes = $form->getAttributes();
            if (!array_key_exists('id', $formAttributes) && array_key_exists('name', $formAttributes)) {
                $formAttributes['id'] = $formAttributes['name'];
            }
            $attributes = array_merge($attributes, $formAttributes);
        }

        $tag = sprintf(' %s ', $this->createAttributesString($attributes));
        return $tag;
    }
}
