<?php
namespace Engine\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class LoginController extends RestfulModuleController
{

    public function indexAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return;
        }
            
        $item = $request->getPost();
        $form = new \User\Form\LoginForm();
        $form->bind($item);
        if ($form->isValid()) {
            $callback = $this->params()->fromPost('callback');
            $callback = $callback ? $callback : '/';

            $item = $form->getData();
            $itemModel = Api::_()->getModel('User\Model\Login');
            $loginResult = $itemModel->setItem($item)->login();

            if($item['rememberMe']){
                $tokenString = $itemModel->createToken();
                //Cookie expired after 60 days
                $this->cookie()->crypt(false)->write('realm', $tokenString, 3600 * 24 * 60);
            }
            $this->redirect()->toUrl($callback);
        } else {
            $item = $form->getData();
        }
        return array(
            'form' => $form,
            'item' => $item,
        );
    }

    public function autoAction()
    {
        $callback = $this->params()->fromQuery('callback', '/');
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
        /*
        $viewModel = new ViewModel();
        $viewModel->setTemplate('blank');
        return $viewModel;
        */
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

        /*
        $client = $oauth->getAdapter()->getHttpClient();
        $client->setUri('https://api.weibo.com/2/users/show.json');
        $client->setParameterGet(array(
            'screen_name' => 'Allo'
        ));
        $response = $client->send();
        p($accessToken);
        p($response->getBody());
        */

        $viewModel = new ViewModel();
        $viewModel->setTemplate('engine/user/register');
        $viewModel->setVariables(array(
            'token' => $accessToken,
            'websiteName' => $websiteName,
            'profileUrl' => $profileUrl,
        ));
        return $viewModel;
    }

}
