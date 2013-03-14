<?php
namespace Epic\Controller;

use Eva\Mvc\Controller\ActionController;
use Eva\View\Model\ViewModel;
use Eva\Api;
use Core\Auth;

class LoginController extends ActionController
{
    protected $addResources = array(
    );

    public function logoutAction()
    {
        $callback = $this->params()->fromQuery('callback');
        if(!$callback && $this->getRequest()->getServer('HTTP_REFERER')){
            $callback = $this->getRequest()->getServer('HTTP_REFERER');
        }
        $callback = $callback ? $callback : '/';
        $model = new ViewModel();
        $auth = Auth::factory();
        $auth->getAuthStorage()->clear();
        $this->cookie()->clear('realm');
        return $this->redirect()->toUrl($callback);
    }

    public function indexAction()
    {
        $this->layout('layout/login');
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return array(
                'query' => $this->params()->fromQuery()
            );
        }
            
        $item = $request->getPost();
        $form = new \User\Form\LoginForm();
        $form->bind($item);
        if ($form->isValid()) {
            $callback = $this->params()->fromPost('callback');
            $callback = $callback ? $callback : '/dashboard/';

            $item = $form->getData();
            $itemModel = Api::_()->getModel('User\Model\Login');
            $itemModel->setItem($item)->login();
            $loginResult = $itemModel->getLoginResult();

            if($item['rememberMe']){
                $tokenString = $itemModel->createToken();
                //Cookie expired after 60 days
                $this->cookie()->crypt(false)->write('realm', $tokenString, 3600 * 24 * 60);
            }

            if($loginResult->isValid()){
                return $this->redirect()->toUrl($callback);
            } else {
                $this->flashMessenger()->setNamespace('login-result')->addMessage($loginResult);
                return $this->redirect()->toUrl('/login/');
            }
        } else {
            $item = $form->getData();
        }
        return array(
            'form' => $form,
            'item' => $item,
            'query' => $this->params()->fromQuery(),
        );
    }

    public function autoAction()
    {
        $callback = $this->params()->fromQuery('callback', '/dashboard/');
        $realm = $this->cookie()->crypt(false)->read('realm');

        if(!$realm){
            $this->cookie()->clear('realm');
            return $this->redirect()->toUrl($callback);
        }

        $itemModel = Api::_()->getModel('User\Model\Login');
        $loginResult = $itemModel->loginByToken($realm);
        if($loginResult->isValid()){
            $tokenString = $itemModel->refreshToken($realm);
            //Cookie expired after 60 days
            $this->cookie()->crypt(false)->write('realm', $tokenString, 3600 * 24 * 60);
        } else {
            $this->cookie()->clear('realm');
        }
        return $this->redirect()->toUrl($callback);
    }


    public function oauthAction()
    {
        $oauth = new \Oauth\OauthService();
        $oauth->setServiceLocator($this->getServiceLocator());
        $oauth->initByAccessToken();
        $accessTokenArray = $oauth->getStorage()->getAccessToken();
        $accessToken = $oauth->getAdapter()->getAccessToken();
        $websiteName = $oauth->getAdapter()->getWebsiteName();
        $profileUrl =  $oauth->getAdapter()->getWebsiteProfileUrl();

        $itemModel = Api::_()->getModel('Oauth\Model\Accesstoken');
        $itemModel->setItem($accessTokenArray)->login();
        $loginResult = $itemModel->getLoginResult();
        if($loginResult && $loginResult->isValid()) {
            $config = $this->getServiceLocator()->get('Config');
            $callback = $config['oauth']['login_url_path'];
            $callback = $callback ? $callback : '/';
            return $this->redirect()->toUrl($callback);
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/user/register');
        $viewModel->setVariables(array(
            'token' => $accessToken,
            'websiteName' => $websiteName,
            'profileUrl' => $profileUrl,
        ));
        return $viewModel;
    }

}
