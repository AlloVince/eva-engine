<?php
namespace Blog\Api\Controller;

use File\Form,
    Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Zend\View\Model\JsonModel;

class CategoryController extends ActionController
{
    public function selectAction($params = null)
    {
        $itemTable = Api::_()->getDbTable('Blog\DbTable\Categories');
        $items = $itemTable->disableLimit()->find('all');
        $select = array();

        foreach($items as $key => $item){
            $select[] = array(
                'label' => $item['categoryName'],
                'value' => $item['id'],
            );
        }

        return $select;
    }

}
