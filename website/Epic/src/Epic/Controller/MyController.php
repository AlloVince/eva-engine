<?php
namespace Epic\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;
use Core\Auth;
use Core\Exception;

class MyController extends ActionController
{

    public function upgradeAction()
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
        $plan = strtolower($plan);

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
    
    public function cityAction()
    {
    }

    public function blogAction()
    {
        $user = Auth::getLoginUser();
        $viewModel = $this->forward()->dispatch('UserController', array(
            'action' => 'blog',
            'id' => $user['userName'],
        )); 
        $viewModel->setTemplate('epic/my/blog');
        return $viewModel;
    }

    public function friendAction()
    {
        $user = Auth::getLoginUser();
        
        $selectQuery = array(
            'user_id' => $user['id'],
            'relationshipStatus' => 'approved',
            'page' => $this->params()->fromQuery('page', 1),
        );

        $itemModel = Api::_()->getModel('User\Model\Friend');
        $items = $itemModel->setItemList($selectQuery)->getFriendList();
        $items->toArray(array(
            'self' => array(
            
            ),
            'join' => array(
                'User' => array(
                    'self' => array(
                        '*'
                    ), 
                    'join' => array(
                        'Profile' => array(
                            '*'
                        ),
                    ),
                    'proxy' => array(
                        'User\Item\User::Avatar' => array(
                            '*',
                            'getThumb()'
                        ),
                    ),
                ),
            ),
        ));

        $paginator = $itemModel->getPaginator();

        return array(
            'items' => $items,
            'query' => $selectQuery,
            'paginator' => $paginator,
        );
    }

    public function albumAction()
    {
        $user = Auth::getLoginUser();
        $viewModel = $this->forward()->dispatch('UserController', array(
            'action' => 'albums',
            'id' => $user['userName'],
            'rows' => 12,
        )); 
        $viewModel->setTemplate('epic/my/album');
        return $viewModel;
    }

    public function groupAction()
    {
        $user = Auth::getLoginUser();
        $viewModel = $this->forward()->dispatch('UserController', array(
            'action' => 'groups',
            'id' => $user['userName'],
        )); 
        $viewModel->setTemplate('epic/my/group');
        return $viewModel;
    }

    public function eventAction()
    {
        $user = Auth::getLoginUser();
        $timenode = $this->params('id');
        $timenode = $timenode ? $timenode : 'ongoing';
        $this->getRequest()->setQuery(new \Zend\Stdlib\Parameters(array(
            'timenode' => $timenode
        )));
        $viewModel = $this->forward()->dispatch('UserController', array(
            'action' => 'events',
            'id' => $user['userName'],
        )); 
        $viewModel->setTemplate('epic/my/event');
        return $viewModel;
    }

    public function registerAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $item = $request->getPost();
            $form = new \User\Form\RegisterForm();
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
                'form' => $form,
                'item' => $item,
            );
        }
    }
}
