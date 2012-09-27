<?php
namespace User\Api\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController;

class RoleController extends ActionController
{
    public function multicheckboxAction($params = null)
    {
        $model = Api::_()->getModel('User\Model\Role');
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
