<?php
namespace Contacts\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Eva\Api,
    Core\Auth;

class ImportController extends AbstractActionController
{
    public function indexAction()
    {
        $user = Auth::getLoginUser();
    
        if(isset($user['isSuperAdmin']) || !$user){
            exit;
        } 
        
        $adapter = $this->params()->fromQuery('service');
        $callback = $this->params()->fromQuery('r');
        $version = (int) $this->params()->fromQuery('version');
        
        if(!$adapter){
            throw new \Oauth\Exception\InvalidArgumentException(sprintf(
                'No oauth service key found'
            ));
        }
        
        $config = $this->getServiceLocator()->get('config');
        $helper = $this->getEvent()->getApplication()->getServiceManager()->get('viewhelpermanager')->get('serverurl');
        $url = $helper() . $config['contacts']['import_url_path'] . '?' . http_build_query(array(
            'r' => $callback,
            'service' => $adapter,
        ));

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

            $import = new \Contacts\ContactsImport($adapterKey, false, array(
                'accessToken' => $accessToken,
                'cacheConfig' => $config['cache']['contacts_import'],
            ));

            $contacts = $import->getContacts();
         //   if (!isset($contacts[$adapter])) {
                $contacts = $import->getUserContactsInfo($contacts);
          //  }
            $import->saveContacts($contacts);

        } catch (\Oauth\Exception\InvalidArgumentException $e) {
            $url = $helper() . $config['oauth']['request_url_path'] . '?' . http_build_query(array(
                'r' => $callback,
                'service' => $adapter,
                'version' => $version
            ));
            return $this->redirect()->toUrl($url);
        }
        
        return $this->redirect()->toUrl($callback);
    }
}
