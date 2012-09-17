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

namespace User\Form;

/**
 * Eva Form will automatic combination form Elements & Validators & Filters
 * Also allow add sub forms and unit validate
 * 
 * @category   Eva
 * @package    Eva_Form
 */
class UserCommonFieldForm extends \Eva\Form\RestfulForm
{
    public function init()
    {
        $itemModel = \Eva\Api::_()->getModelService('User\Model\Field');
        $items = $itemModel->setItemList(array(
            'applyToAll' => 1
        ))->getFieldList();

        $items = $items->toArray(array(
            'self' => array(
            ),
            'join' => array(
                'Fieldoption' => array(
                    '*',
                ),
            ),
        ));

        foreach($items as $item){
            $this->baseElements[] = $itemModel->fieldToElement($item);
        }

        parent::init();
    }
}
