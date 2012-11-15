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
        $importUrl = $helper() . $config['contacts']['import_url_path'] . '?' . http_build_query(array(
            'r' => $callback,
            'service' => $adapter,
        ));
        
        $import = new \Contacts\ContactsImport($adapter, false, array(
            'cacheConfig' => $config['cache']['contacts_import'],
        ));
        $contacts = $import->getStorage()->loadContacts();
        
        if ($contacts) {
            return $this->redirect()->toUrl($callback);
        }

        $oauth = new \Oauth\OauthService();
        $accessTokenArray = $oauth->getStorage()->getAccessToken();

        if (!$accessTokenArray || (isset($accessTokenArray['adapterKey']) && $accessTokenArray['adapterKey'] != $adapter)) {
            $url = $helper() . $config['oauth']['request_url_path'] . '?' . http_build_query(array(
                'r' => $importUrl,
                'service' => $adapter,
                'version' => $version
            ));
            return $this->redirect()->toUrl($url);
        }
        
        $import->setAccessToken($accessTokenArray['token']);
        $contacts = $import->getContacts();
        $import->getStorage()->saveContacts($contacts);
        
        $accessToken = $oauth->getStorage()->clearAccessToken();
        
        return $this->redirect()->toUrl($callback);
    }
}
