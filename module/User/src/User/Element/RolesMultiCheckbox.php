<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Form
 */

namespace User\Element;

use Eva\Api;
use Zend\Form\Element\MultiCheckbox;

/**
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 */
class RolesMultiCheckbox extends MultiCheckbox
{

    public function getValueOptions()
    {
        $model = Api::_()->getModelService('User\Model\Role');
        $items = $model->getRoleList();
        $valueOptions = array();
        foreach($items as $item){
            $valueOptions[] = array(
                'label' => $item['roleName'],
                'value' => $item['id'],
            );
        }
        return $valueOptions;
    }
}
