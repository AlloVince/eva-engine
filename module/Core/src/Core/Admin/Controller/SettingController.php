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

        $view['flashMessenger'] = $this->flashMessenger()->getMessages();
        $view['component'] = $config['page_components'][$id];
        return $view;
    }

    public function restPostSettingComponent()
    {
        $postData = $this->getRequest()->getPost();
        $componentName = $postData['name'];
        $view = array();
        if(!$componentName){
            return $view; 
        }

        $api = Api::_();
        $config = $api->getConfig();
        if(!isset($config['page_components'][$componentName]) || !$config['page_components'][$componentName]){
            return $view; 
        }
        $component = $config['page_components'][$componentName];
        $path = $api->getModulePath($component['module']);
        $filepath = $path . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . $component['path'] . '.phtml';

        if(file_exists($filepath) && is_writable($filepath)){

            $handle = fopen($filepath, 'wa');
            fwrite($handle, $postData['content']);
            fclose($handle);

            $this->flashMessenger()->addMessage('file-edit-succeed');
            return $this->redirect()->toUrl('/admin/core/setting/component/' . $componentName);

        } else {

            if(!file_exists($filepath)){
                $view['flashMesseger'] = array('file-not-exist');
            } else {
                $view['flashMesseger'] = array('file-not-writable');
            }
        
        }

        $view['component'] = $component;
        return $view;
    }
}
