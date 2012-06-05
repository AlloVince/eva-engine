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
            $form = new \User\Form\AdminLoginForm();
			$form->enableFilters()->setData($request->post());
            if ($form->isValid()) {
				//p(1);
			} else {
				//p(2);
			}
		}

		return array(
			'form' => $form,
			'post' => $request->post(),
		);
	
	}
}
