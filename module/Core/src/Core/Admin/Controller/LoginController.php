<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class LoginController extends ActionController
{

    protected function superAdminLogin()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        
        $flashMesseger = array();
        $form = Api::_()->getForm('Core\Form\SuperAdminLoginForm');

        $viewVariables = array(
            'form' => $form,
            'user' => $postData,
            'flashMessenger' => &$flashMesseger
        );

        if (!$request->isPost()) {
            return $viewVariables;
        }

        $form->init()->enableFilters()->setData($postData);
        if (!$form->isValid()) {
            return $viewVariables;
        }

        $config = Api::_()->getConfig();
        if($postData['userName'] != $config['superadmin']['username']){
            $flashMesseger = array('user-name-failed');
            return $viewVariables;
        }

        if($postData['password'] != $config['superadmin']['password']){
            $flashMesseger = array('password-failed');
            return $viewVariables;
        }

        $this->redirect()->toUrl('/admin/core/dashboard');
    }

    public function indexAction()
    {

        $viewVariables = $this->superAdminLogin();
        $model = new ViewModel();
        $this->layout('layout/adminindex');
        $model->setTemplate('core/index');
        $model->setVariables($viewVariables);
        return $model;
    }
}
