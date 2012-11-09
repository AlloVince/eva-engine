<?php
namespace User\Controller;

use Eva\Mvc\Controller\ActionController,
    Core\Exception,
    Core\Auth,
    Eva\Api,
    Eva\View\Model\ViewModel;

class UpgradeController extends ActionController
{
    public function indexAction()
    {
        $user = Auth::getLoginUser();
    
        if(isset($user['isSuperAdmin']) || !$user){
            exit;
        } 
        
        if (!Api::_()->isModuleLoaded('Payment')) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Payment module not loaded'
            )); 
        }
        
        $adapter = $this->params()->fromQuery('adapter');
        $callback = $this->params()->fromQuery('callback');
        $plan = $this->params()->fromQuery('plan');
       
        $config = \Eva\Api::_()->getModuleConfig('User');
        
        if(!isset($config['upgrade']['plans'][$plan])){
            throw new Exception\InvalidArgumentException(sprintf(
                'No upgrade plan found'
            ));
        }
        
        if(!$adapter){
            throw new Exception\InvalidArgumentException(sprintf(
                'No payment adapter key found'
            ));
        }

        if(!$callback){
            throw new Exception\InvalidArgumentException(sprintf(
                'No callback url found'
            ));
        }

        $plan = $config['upgrade']['plans'][$plan];

        $config = $this->getServiceLocator()->get('config');
        $helper = $this->getEvent()->getApplication()->getServiceManager()->get('viewhelpermanager')->get('serverurl');
        
        $queryString = http_build_query(array(
            'adapter'  => $adapter,
            'amount'   => $plan['amount'],
            'callback' => $callback,
            'data'     => $plan,
        ));
        $paymentSecretKey = $config['payment']['paymentSecretKey'];
        if(!$paymentSecretKey){
            throw new Exception\InvalidArgumentException(sprintf(
                'Payment config error'
            ));
        }
        $signed = md5($queryString . $paymentSecretKey);
        
        $url = $helper() . $config['payment']['request_url_path'] . '?' . http_build_query(array(
            'adapter'  => $adapter,
            'amount'   => $plan['amount'],
            'callback' => $callback,
            'data'     => $plan,
            'signed'   => $signed
        ));
        
        $roleModel = Api::_()->getModel('User\Model\Role');
        $role = $roleModel->getRole($plan['roleKey']);
        if (!isset($role['id'])) {
            throw new Exception\InvalidArgumentException(sprintf(
                'No role found'
            ));
        }
        $itemModel = \Eva\Api::_()->getModel('User\Model\RoleUser');
        $roleUser = $itemModel->getRoleUser($user['id'], $role['id']);

        $now = \Eva\Date\Date::getNow(); 
        if (!isset($roleUser['user_id'])) {
            $data['user_id']     = $user['id'];
            $data['role_id']     = $role['id'];
            $data['status']      = 'pending';
            $data['pendingTime'] = $now; 
            $itemModel->setItem($data)->createRoleUser();
        }

        return $this->redirect()->toUrl($url);
    }
}
