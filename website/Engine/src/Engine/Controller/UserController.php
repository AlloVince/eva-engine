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

    public function loginAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $item = $request->getPost();
            $form = new \User\Form\LoginForm();
            $form->bind($item);
            if ($form->isValid()) {
                $callback = $this->params()->fromPost('callback');
                $callback = $callback ? $callback : '/';

                $item = $form->getData();
                $itemModel = Api::_()->getModel('User\Model\Login');
                $itemModel->setItem($item)->login();

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
}
