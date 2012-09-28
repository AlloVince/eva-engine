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
            $form = new \User\Form\RegisterForm();
            $form->bind($request->getPost());
            if ($form->isValid()) {
                $callback = $this->params()->fromPost('callback');
                $callback = $callback ? $callback : '/';
                $this->redirect()->toUrl($callback);
            } else {
            }
            return array(
                'form' => $form,
                'item' => $request->getPost(),
            );
        }
    }
}
