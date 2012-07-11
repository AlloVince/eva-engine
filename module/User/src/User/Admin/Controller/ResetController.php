<?php
namespace User\Admin\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class ResetController extends RestfulModuleController
{
    protected $renders = array(
        'restPutReset' => 'reset/index',
    );

    public function restIndexReset()
    {

    }

    public function restPutReset()
    {
        $request = $this->getRequest();
        $form = Api::_()->getForm('User\Form\ResetForm');
        if ($request->isPost()) {
            $form->init()->setData($request->getPost())->enableFilters();
            if ($form->isValid()) {

                $mail = new \Core\Mail();
                $mail->getMessage()->addFrom("allo.vince@gmail.com", "AlloVince")
                ->addTo("allo.vince@gmail.com")
                ->setSubject("Sending an email from Zend\Mail!")
                ->setData(array(
                    'foo' => 'bar'
                ))
                ->setTemplatePath(EVA_MODULE_PATH . '/User/view/')
                ->setTemplate('mail/reset');
                $mail->send();

            } else {
            }
            //$mail->send();
        }

        return array(
            'form' => $form,
            'post' => $request->getPost(),
        );
    
    }
}
