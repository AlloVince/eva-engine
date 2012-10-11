<?php
namespace Oauth\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Oauth\OauthService,
    Oauth\Exception;

class AccessController extends AbstractActionController
{
    public function indexAction()
    {
        $callback = $this->params()->fromQuery('callback');
        if(!$callback){
            throw new Exception\InvalidArgumentException(sprintf(
                'No oauth callback found'
            ));
        }

        $config = $this->getServiceLocator()->get('config');
        $helper = $this->getEvent()->getApplication()->getServiceManager()->get('viewhelpermanager')->get('serverurl');
        $url = $helper() . $config['oauth']['access_url_path'] . '?' . http_build_query(array(
            'callback' => urlencode($callback),
        ));
        $options = array(
            'adapter' => 'Douban',
            'storage' => 'Session',
            'version' => OauthService::VERSION_OAUTH1,
            'callback' => $url,
        );
        $oauth = OauthService::factory($options, $this->getServiceLocator());

        $query = $this->params()->fromQuery();
        $requestToken = $oauth->getStorage()->getRequestToken();
        $accessToken = $oauth->getAdapter()->getAccessToken($query, $requestToken);
        $request = $oauth->getAdapter()->getRequest();
        $response = $oauth->getAdapter()->getResponse();

        $accessTokenArray = $oauth->getAdapter()->accessTokenToArray($accessToken);
        $oauth->getStorage()->saveAccessToken($accessTokenArray);
        return $this->redirect()->toUrl($callback);
    }
}
