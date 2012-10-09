<?php
namespace User\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Core\Auth,
    Zend\Authentication\Result;

class LoginController extends RestfulModuleController
{
    public function restPutLogin()
    {
        $this->layout('layout/adminblank');
        $item = $this->params()->fromPost();
        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/admin/core/dashboard';
        $viewVariables = array();

        if (!$this->getRequest()->isPost()) {
            return $viewVariables;
        }

        $form = new \User\Form\AdminLoginForm();
        $form->bind($item);
        if ($form->isValid()) {
            $item = $form->getData();

            if($item['isSuperAdmin']){
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
                    return $this->redirect()->toUrl($callback);
                }
            } else {
                $loginModel = Api::_()->getModel('User\Model\Login');
                $authResult = $loginModel->loginByPassword($item['loginName'], $item['inputPassword']);
                if($authResult->isValid()){
                    $user = Auth::getLoginUser();
                    if(!isset($user['Roles']) || !in_array('Admin', $user['Roles'])){
                        $this->getResponse()->setStatusCode(401);
                        $this->flashMessenger()->addMessage('permission-not-enough');
                        return $this->redirect()->toUrl('/admin/');
                    }
                    return $this->redirect()->toUrl($callback);
                }
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
        } else {
            $item = $form->getData();
        }

        return array(
            'form' => $form,
            'item' => $item,
        );

    }
}
