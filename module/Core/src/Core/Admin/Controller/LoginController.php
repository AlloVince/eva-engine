<?php
namespace Core\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Zend\Authentication\Result,
    Core\Form,
    Core\Auth;
    

class LoginController extends ActionController
{

    protected function superAdminLogin()
    {
        $postData = $this->params()->fromPost();
        
        $form = new Form\SuperAdminLoginForm();
        $viewVariables = array(
            'form' => $form,
            'item' => $postData,
        );

        if (!$this->getRequest()->isPost()) {
            return $viewVariables;
        }

        $form->bind($postData);
        if (!$form->isValid()) {
            return $viewVariables;
        }

        $auth = new Auth('Config', 'Session');
        //$auth = new Auth('DbTable', 'Session');
        $authResult = $auth->authenticate(array(
            'username' => $postData['userName'],
            'password' => $postData['password'],
        ));

        if($authResult->isValid()){
            $auth->getAuthStorage()->write($authResult->getIdentity());
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
        $this->layout('layout/adminblank');
        $model->setTemplate('core/index');
        $model->setVariables($viewVariables);
        return $model;
    }
}
