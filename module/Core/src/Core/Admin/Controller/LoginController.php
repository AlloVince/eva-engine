<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;
use Zend\Authentication\Result;

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

        $auth = new \Core\Auth();
        $authResult = $auth->configAuthenticate($postData['userName'], $postData['password']);

        if($authResult->isValid()){
            $auth->getStorage()->write($authResult->getIdentity());
            $callback = $this->params()->fromPost('callback');
            $callback = $callback ? $callback : '/admin/core/dashboard';
            $this->redirect()->toUrl($callback);
            return array();
        }

        switch($authResult->getCode()){
            case Result::FAILURE_IDENTITY_NOT_FOUND:
            $flashMesseger = array('user-name-failed');
            return $viewVariables;
            case Result::FAILURE_CREDENTIAL_INVALID:
            $flashMesseger = array('password-failed');
            return $viewVariables;
            default:
            return $viewVariables;
        }
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
