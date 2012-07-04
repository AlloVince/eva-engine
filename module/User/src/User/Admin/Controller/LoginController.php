<?php
namespace User\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class LoginController extends RestfulModuleController
{
    public function restPutLogin()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = Api::_()->getForm('User\Form\AdminLoginForm');
            $form->init()->enableFilters()->setData($request->getPost());
            if ($form->isValid()) {
            } else {
            }
        }

        return array(
            'form' => $form,
            'post' => $request->getPost(),
        );
    
    }
}
