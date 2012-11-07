<?php
namespace Contacts\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Eva\Api,
    Core\Auth;

class InviteController extends AbstractActionController
{
    public function indexAction()
    {
        $user = Auth::getLoginUser();
    
        if(isset($user['isSuperAdmin']) || !$user){
            exit;
        } 
        
        $callback = $this->params()->fromQuery('r');
        $emails = $this->params()->fromPost('email');
        
        if (!$emails) {
            exit;
        }

        return $this->redirect()->toUrl($callback);
    }
}
