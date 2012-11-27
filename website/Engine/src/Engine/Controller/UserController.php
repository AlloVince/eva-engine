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
        if (!$request->isPost()) {
            return;
        }

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

            $this->redirect()->toUrl($callback);
        } else {
        }
        return array(
            'token' => $accessToken,
            'form' => $form,
            'item' => $item,
        );
    }

    public function pricingAction()
    {
        $user = Auth::getLoginUser();

        if(isset($user['isSuperAdmin']) || !$user){
            exit;
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

    public function inviteAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $adapter = $this->params()->fromQuery('service');

        $user = Auth::getLoginUser();

        if(isset($user['isSuperAdmin']) || !$user){
            exit;
        } 

        if(!$adapter){
            throw new \Contacts\Exception\InvalidArgumentException(sprintf(
                'No contacts service key found'
            ));
        }

        $config = $this->getServiceLocator()->get('config');
        $import = new \Contacts\ContactsImport($adapter, false, array(
            'cacheConfig' => $config['cache']['contacts_import'],
        ));
        $contacts = $import->getStorage()->loadContacts();

        $itemModel = \Eva\Api::_()->getModel('Contacts\Model\Contacts');
        $itemModel->setUser($user);
        $itemModel->setService($adapter);
        $contacts = $itemModel->getUserContactsInfo($contacts);

        if ($id == 'add') {
            $count = isset($contacts['onSiteContactsCount']) ? $contacts['onSiteContactsCount'] : 0;
            $contacts = isset($contacts['onSiteContacts']) ? $contacts['onSiteContacts'] : array();
        } else {
            $count = isset($contacts['outSiteContactsCount']) ? $contacts['outSiteContactsCount'] : 0;
            $contacts = isset($contacts['outSiteContacts']) ? $contacts['outSiteContacts'] : array();
        }

        return array(
            'id'       => $id,
            'count'    => $count,
            'contacts' => $contacts,
            'service'  => $adapter,
        );
    }
}
