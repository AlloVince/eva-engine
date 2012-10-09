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
        $item = $this->params()->fromPost();
        
        $form = new Form\SuperAdminLoginForm();
        $viewVariables = array(
            'form' => $form,
            'item' => $item,
        );

        if (!$this->getRequest()->isPost()) {
            return $viewVariables;
        }

        $form->bind($item);
        if (!$form->isValid()) {
            return $viewVariables;
        }

        $auth = new Auth('Config', 'Session');
        $authResult = $auth->authenticate(array(
            'username' => $item['loginName'],
            'password' => $item['inputPassword'],
        ));

        if($authResult->isValid()){
            $auth->saveLoginUser(array(
                'id' => '0',
                'userName' => $item['loginName'],
                'isSuperAdmin' => true,
            ));
            $callback = $this->params()->fromPost('callback');
            $callback = $callback ? $callback : '/admin/core/dashboard';
            $this->redirect()->toUrl($callback);
            return array();
        }

        switch($authResult->getCode()){
            case Result::FAILURE_IDENTITY_NOT_FOUND:
            $this->flashMessenger()->addMessage('user-name-failed');
            break;
            case Result::FAILURE_CREDENTIAL_INVALID:
            $this->flashMessenger()->addMessage('password-failed');
            break;
            default:;
        }

        return $viewVariables;
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
