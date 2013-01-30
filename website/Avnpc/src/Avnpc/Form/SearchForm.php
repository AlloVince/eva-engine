<?
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Avnpc\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class SearchForm extends \Eva\Form\Form
{
    protected $mergeElements = array(
        'q' => array (
            'name' => 'q',
            'type' => 'text',
            'options' => array (
                'label' => 'Keyword',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'price' => array (
            'name' => 'price',
            'type' => 'text',
            'options' => array (
                'label' => 'Original Price',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'nick' => array (
            'name' => 'nick',
            'type' => 'text',
            'options' => array (
                'label' => 'Nick',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
        'page' => array (
            'name' => 'page',
            'type' => 'text',
            'options' => array (
                'label' => 'Page',
            ),
            'attributes' => array (
                'value' => '',
            ),
        ),
    );

    protected $mergeFilters = array(
        'q' => array (
            'name' => 'q',
            'required' => true,
            'filters' => array (
                'stringTrim' => array (
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array (
            ),
        ),
    );

    public function prepareData($data)
    {
        if(!$data['page']){
            $data['page'] = 1;
        }
        return $data;
    }
}
