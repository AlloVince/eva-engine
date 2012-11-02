<?php
namespace Engine\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Core\Auth,
    Eva\View\Model\ViewModel;

class UserController extends RestfulModuleController
{

    public function registerAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $item = $request->getPost();

            $oauth = new \Oauth\OauthService();
            $oauth->setServiceLocator($this->getServiceLocator());
            $oauth->initByAccessToken();
            $accessToken = $oauth->getAdapter()->getAccessToken();

            $form = $accessToken ? new \User\Form\QuickRegisterForm : new \User\Form\RegisterForm();
            $form->bind($item);
            if ($form->isValid()) {
                $callback = $this->params()->fromPost('callback');
                $callback = $callback ? $callback : '/';

                $item = $form->getData();
                $itemModel = Api::_()->getModel('User\Model\Register');
                $itemModel->setItem($item)->register();
                $this->redirect()->toUrl($callback);
            } else {
            }
            return array(
                'token' => $accessToken,
                'form' => $form,
                'item' => $item,
            );
        }
    }

    public function pricingAction()
    {
        $user = Auth::getLoginUser();
    
        if(isset($user['isSuperAdmin']) || !$user){
            exit;;
        } 
        
        $itemModel = Api::_()->getModel('User\Model\User');
        $item = $itemModel->getUser($user['id']);

        $item = $item->toArray(array(
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
                'Account' => array('*'),
            ),
        ));
        
        return array(
            'item' => $item,
        );
    }

    public function contactsAction()
    {
    }

    public function exportAction()
    {
   /*     $user = Auth::getLoginUser();

        if(isset($user['isSuperAdmin']) || !$user){
            exit;;
        } 
    */    
        $adapter = $this->getEvent()->getRouteMatch()->getParam('id');
        $callback = $this->params()->fromQuery('r');
        $version = (int) $this->params()->fromQuery('version');
        
        if(!$adapter){
            throw new \Oauth\Exception\InvalidArgumentException(sprintf(
                'No oauth service key found'
            ));
        }

        $oauth = new \Oauth\OauthService();
        $oauth->setServiceLocator($this->getServiceLocator());

        try {
            $oauth->initByAccessToken();
            $accessTokenClass = $oauth->getAdapter()->getAccessToken();

            $accessToken = $accessTokenClass->access_token;
            $adapterKey = $accessTokenClass->adapterKey;
            $expireTime = $accessTokenClass->expireTime;
            
            if ($adapterKey != $adapter) {
                throw new \Oauth\Exception\InvalidArgumentException(sprintf(
                    'Oauth service key not match'
                )); 
            }

            $export = new \Contacts\ContactsExport($adapterKey, false, array(
                'accessToken' => $accessToken,
            ));

            $contacts = $export->getContacts();

        } catch (\Oauth\Exception\InvalidArgumentException $e) {
            $config = $this->getServiceLocator()->get('config');
            $helper = $this->getEvent()->getApplication()->getServiceManager()->get('viewhelpermanager')->get('serverurl');

            $url = $helper() . $config['oauth']['request_url_path'] . '?' . http_build_query(array(
                'r' => $callback,
                'service' => $adapter,
                'version' => $version
            ));
            return $this->redirect()->toUrl($url);
        }
p($contacts);exit;
        return array(
            'items' => $contacts,
        );
    }
}
