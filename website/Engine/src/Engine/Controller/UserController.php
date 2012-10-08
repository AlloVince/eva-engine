<?php
namespace Engine\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class UserController extends RestfulModuleController
{

    public function registerAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $item = $request->getPost();
            $form = new \User\Form\RegisterForm();
            $form->bind($item);
            if ($form->isValid()) {
                $callback = $this->params()->fromPost('callback');
                $callback = $callback ? $callback : '/';

                $item = $form->getData();
                $itemModel = Api::_()->getModel('User\Model\Register');
                $itemModel->setItem($item)->register();

                //$this->redirect()->toUrl($callback);
            } else {
            }
            return array(
                'form' => $form,
                'item' => $item,
            );
        }
    }

    public function autologinAction()
    {
        $callback = $this->params()->fromQuery('callback', '/');
        $realm = $this->cookie()->crypt(false)->read('realm');

        if(!$realm){
            return $this->redirect()->toUrl($callback);
        }

        $itemModel = Api::_()->getModel('User\Model\Login');
        $loginResult = $itemModel->loginByToken($realm);
        if($loginResult->isValid()){
        
        } else {
            $this->cookie()->clear('realm');
        }
        //return $this->redirect()->toUrl($callback);
        $viewModel = new ViewModel();
        $viewModel->setTemplate('blank');
        return $viewModel;
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return;
        }
            
        $item = $request->getPost();
        $form = new \User\Form\LoginForm();
        $form->bind($item);
        if ($form->isValid()) {
            $callback = $this->params()->fromPost('callback');
            $callback = $callback ? $callback : '/';

            $item = $form->getData();
            $itemModel = Api::_()->getModel('User\Model\Login');
            $loginResult = $itemModel->setItem($item)->login();

            if($item['rememberMe']){
                $tokenString = $itemModel->createToken();
                //Cookie expired after 60 days
                $this->cookie()->crypt(false)->write('realm', $tokenString, 3600 * 24 * 60);
            }
            p($itemModel->getLoginResult());
            //$this->redirect()->toUrl($callback);
        } else {
            $item = $form->getData();
        }
        return array(
            'form' => $form,
            'item' => $item,
        );
    }
}
