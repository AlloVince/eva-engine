<?php
namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\RestfulModuleController;
use Eva\Api;
use Core\Auth;

class NewsletterController extends RestfulModuleController
{
    protected $renders = array(
        'restPostNewsletter' => 'blank',
        'restDeleteNewsletter' => 'blank',
    );


    public function restPostNewsletter()
    {
        $postData = $this->params()->fromPost();

        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/home/';

        $user = Auth::getLoginUser();
        $userModel = Api::_()->getModel('User\Model\User');
        $mine = $userModel->getUser($user['id']);
        
        if (!$mine) {
            exit;
        }
        
        $postTable = Api::_()->getDbTable('Core\DbTable\Newsletters');

        $postTable->where(array(
            'user_id' => $mine['id'],
        ))->remove();
        $postTable->create(array(
            'user_id' => $mine['id'],
            'email' => $mine['email'],
        ));

        $this->redirect()->toUrl($callback);
    }

    public function restDeleteNewsletter()
    {
        $postData = $this->params()->fromPost();

        $callback = $this->params()->fromPost('callback');
        $callback = $callback ? $callback : '/home/';

        $user = Auth::getLoginUser();
        $userModel = Api::_()->getModel('User\Model\User');
        $mine = $userModel->getUser($user['id']);

        if (!$mine) {
            exit;
        }

        $postTable = Api::_()->getDbTable('Core\DbTable\Newsletters');

        $postTable->where(array(
            'user_id' => $mine['id'],
        ))->remove();

        $this->redirect()->toUrl($callback);
    }
}
