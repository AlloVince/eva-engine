<?php
namespace User\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Core\Auth;

class LoginController extends RestfulModuleController
{
    public function restPutLogin()
    {
        $this->layout('layout/adminblank');
        $item = $this->params()->fromPost();
        if ($this->getRequest()->isPost()) {
            $form = new \User\Form\AdminLoginForm();
            $form->bind($item);
            if ($form->isValid()) {
                $item = $form->getData();

                $loginModel = Api::_()->getModel('User\Model\Login');
                $authResult = $loginModel->loginByPassword($item['userName'], $item['password']);

                if($authResult->isValid()){
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
            } else {
                $item = $form->getData();
            }
        }

        return array(
            'form' => $form,
            'item' => $item,
        );

    }
}
