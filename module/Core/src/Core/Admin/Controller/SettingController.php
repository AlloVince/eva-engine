<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class SettingController extends RestfulModuleController
{
    protected $addResources = array(
        'components',    
        'component',    
    );

    public function restIndexSetting()
    {

    }

    public function restGetSettingComponents()
    {
        $api = Api::_();
        $config = $api->getConfig();
        $view = array();
        if(!isset($config['page_components']) || !$config['page_components']){
            return $view; 
        }

        $view['components'] = $config['page_components'];
        return $view;
    }

    public function restGetSettingComponent()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');

        $view = array();
        if(!$id){
            return $view; 
        }

        $api = Api::_();
        $config = $api->getConfig();
        if(!isset($config['page_components'][$id]) || !$config['page_components'][$id]){
            return $view; 
        }

        $view['component'] = $config['page_components'][$id];
        return $view;
    }
}
