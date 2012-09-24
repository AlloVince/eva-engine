<?php
namespace User\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class LoginController extends RestfulModuleController
{
    public function restPutLogin()
    {
        $this->layout('layout/adminblank');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new \User\Form\AdminLoginForm();
            $form->bind($request->getPost());
            if ($form->isValid()) {
                $callback = $this->params()->fromPost('callback');
                $callback = $callback ? $callback : '/admin/core/dashboard';
                $this->redirect()->toUrl($callback);
            } else {
            }
        }

        return array(
            'form' => $form,
            'post' => $request->getPost(),
        );
    
    }
}
