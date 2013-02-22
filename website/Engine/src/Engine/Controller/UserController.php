<?php
namespace Engine\Controller;

use Eva\Api;
use Eva\Mvc\Controller\ActionController;
use Eva\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Core\Auth;
use Oauth\OauthService;

class UserController extends ActionController
{
    protected $user;

    public function userAction()
    {
        if($this->user){
            return $this->user;
        }

        $userId = $this->getEvent()->getRouteMatch()->getParam('id');
        if(!$userId){
            return array();
        }
        $userModel = Api::_()->getModel('User\Model\User');
        $user = $userModel->getUser($userId);
        if(!$user){
            return array();
        }
        $user = $user->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Profile' => array(
                    '*'
                ),
                'Roles' => array(
                    '*'
                ),
                'FriendsCount' => array(
                ),
                'Tags' => array(
                    '*'
                ),
            ),
            'proxy' => array(
                'User\Item\User::Avatar' => array(
                    '*',
                    'getThumb()'
                ),
                'User\Item\User::Header' => array(
                    '*',
                    'getThumb()'
                ),
                'Oauth\Item\Accesstoken::Oauth' => array(
                    '*'
                ),
                'Blog\Item\Post::UserPostCount' => array(
                ),
                'Event\Item\EventUser::EventCount' => array(
                ),
            ),
        ));
        return $this->user = $user;
    }

    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getServiceLocator()->get('Application')->getEventManager();
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'setUserToView'), 100);
    }

    public function setUserToView($event)
    {
        $user = $this->userAction();
        $viewModel = $this->getEvent()->getViewModel();
        $viewModel->setVariables(array(
            'user' => $user,
            'viewAsGuest' => 1
        ));
        $viewModelChildren = $viewModel->getChildren();
        foreach($viewModelChildren as $childViewModel){
            $childViewModel->setVariables(array(
                'user' => $user,
                'viewAsGuest' => 1
            ));
        }
    }

    public function registerAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $item = $request->getPost();

            $oauth = new \Oauth\OauthService();
            $accessToken = array();
            if($oauth->getStorage()->getAccessToken()) {
                $oauth->setServiceLocator($this->getServiceLocator());
                $oauth->initByAccessToken();
                $accessToken = $oauth->getAdapter()->getAccessToken();
            }

            $form = $accessToken ? new \User\Form\QuickRegisterForm : new \User\Form\RegisterForm();
            $form->bind($item);
            if ($form->isValid()) {
                $callback = $this->params()->fromPost('callback');
                $callback = $callback ? $callback : '/';

                $item = $form->getData();
                $itemModel = Api::_()->getModel('User\Model\Register');
                $itemModel->setItem($item)->register();

                $userItem = $itemModel->getItem();
                $codeItem = $itemModel->getItem('User\Item\Code');
                $mail = new \Core\Mail();
                $mail->getMessage()
                    ->setSubject("Please Confirm Your Email Address")
                    ->setData(array(
                        'user' => $userItem,
                        'code' => $codeItem,
                    ))
                    ->setTo($userItem->email, $userItem->userName)
                    ->setTemplatePath(Api::_()->getModulePath('User') . '/view/')
                    ->setTemplate('mail/active');
                $mail->send();

                $this->redirect()->toUrl($callback);
            } else {
            }
            return array(
                'token' => $accessToken,
                'form' => $form,
                'item' => $item,
            );
        } else {
            return array(
                'item' => $this->getRequest()->getQuery()
            );
        }
    }
}
