<?php
namespace Epic\Controller;

use Eva\Api;
use Eva\Mvc\Controller\ActionController;
use Eva\View\Model\ViewModel;
use Core\Auth;
use Epic\Form;


class AccountController extends ActionController
{
    public function activeAction()
    {
        $itemModel = Api::_()->getModel('User\Model\Code');
        $itemModel->setItem(array(
            'code' => $this->params()->fromQuery('code'),
            'codeType' => 'activeAccount',
        ));
        if($itemModel->isValid()){
            $itemModel->activeAccount();
        }

        return array(
            'code' => $itemModel->getResultCode(),
            'messages' => $itemModel->getMessages(),
        );
    }

    public function privacyAction()
    {
        $itemModel = Api::_()->getModel('User\Model\Privacy');
        $user = Auth::getLoginUser();
        $privacy = $itemModel->getPrivacy($user['id']);

        $roles = \User\Model\Privacy::$privacyRoles;

        $permissions = array(
            'profile' => array(
                'permissionKey' => 'profile',
                'permissionName' => 'View Timeline',
                'permissionDiscription' => 'View My Basic Info',
            ),
            'blog' => array(
                'permissionKey' => 'blog',
                'permissionName' => 'View My Blog',
                'permissionDiscription' => 'View My Blog',
            ),
            'album' => array(
                'permissionKey' => 'album',
                'permissionName' => 'View My Album',
                'permissionDiscription' => 'View My Album',
            ),
            'event' => array(
                'permissionKey' => 'event',
                'permissionName' => 'View My Joined Events',
                'permissionDiscription' => 'View My Joined Events',
            ),
            'group' => array(
                'permissionKey' => 'group',
                'permissionName' => 'View My Joined Groups',
                'permissionDiscription' => 'View My Joined Groups',
            ),
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $this->params()->fromPost();
            $item = array(
                'user_id' => $user['id'],
                'setting' => \Zend\Json\Json::encode($postData['permission'])
            );
            $itemModel->setItem($item)->savePrivacy();
            $callback = $this->params()->fromPost('callback');
            $callback = $callback ? $callback : '/account/privacy/';
            $this->redirect()->toUrl($callback);
        }

        $permissionForm = new \Eva\Form\Form();
        $options = array();
        foreach($roles as $role){
            $options[] = array(
                'label' => $role['roleName'],
                'value' => $role['roleKey'],
            );
        }
        foreach($permissions as $permission){
            $permissionForm->add(array(
                'name' => $permission['permissionKey'],
                'type' => 'Zend\Form\Element\Select',
                'options' => array(
                    'label' => $permission['permissionName'],
                    'value_options' => $options,
                ),
            ));
        }

        return array(
            'form' => $permissionForm,
            'roles' => $roles,
            'permissions' => $permissions,
            'item' => $privacy,
        );
    }

    public function profileAction()
    {
        $request = $this->getRequest();
        $form = array();

        if ($request->isPost()) {
            $item = $request->getPost();
            $form = new Form\AccountEditForm();
            $form->useSubFormGroup();
            $form->bind($item);
            if ($form->isValid()) {
                $callback = $this->params()->fromPost('callback');
                $callback = $callback ? $callback : '/';

                $item = $form->getData();
                $itemModel = Api::_()->getModel('Epic\Model\User');
                $itemModel->setItem($item)->saveUser();
                $this->redirect()->toUrl($callback);
            } else {
                $item = $form->getData();
            }
        } else {
            $user = Auth::getLoginUser();
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
                    'Tags' => array(
                        '*'
                    ),
                ),
                'proxy' => array(
                    'User\Item\User::Avatar' => array(
                        '*',
                        'getThumb()'
                    ),
                )
            ));
        }

        return array(
            'item' => $item,
            'form' => $form,
        );
    }

    public function headerAction()
    {
        $request = $this->getRequest();
        $user = Auth::getLoginUser();
        $itemModel = Api::_()->getModel('User\Model\User');
        $item = $itemModel->getUser($user['id']);

        $item = $item->toArray(array(
            'self' => array(
                '*',
            ),
            'proxy' => array(
                'User\Item\User::Header' => array(
                    '*',
                    'getThumb()'
                ),
            )
        ));
        return array(
            'item' => $item,
        );
    }

    public function changeheaderAction()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $form = new \User\Form\ImageUserForm();
        $form->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('User\Model\ImageUser');

            $itemId = $itemModel->setItem($postData)->changeImage();
            $this->redirect()->toUrl('/account/header/');
        } else {
            //p($form->getMessages());
        }


        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/account/header');
        $viewModel->setVariables(array(
            'form' => $form
        ));
        return $viewModel;
    }


    public function settingAction()
    {
        $request = $this->getRequest();
        $user = Auth::getLoginUser();
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
            ),
        ));
        return array(
            'item' => $item,
        );
    }

    public function membershipAction()
    {

    }

    public function changeemailAction()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $form = new \User\Form\ChangeEmailForm();
        $form->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('User\Model\Account');

            $itemId = $itemModel->setItem($postData)->changeEmail();
            $this->redirect()->toUrl('/account/setting/');
        } else {
            //p($form->getMessages());
        }


        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/account/setting');
        $viewModel->setVariables(array(
            'emailForm' => $form
        ));
        return $viewModel;
    }

    public function changepasswordAction()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $form = new \User\Form\ChangePasswordForm();
        $form->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('User\Model\Account');

            $itemId = $itemModel->setItem($postData)->changePassword();
            $this->redirect()->toUrl('/account/setting/');
        } else {
            //p($form->getMessages());
        }


        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/account/setting');
        $viewModel->setVariables(array(
            'pwForm' => $form
        ));
        return $viewModel;
    }

    public function importAction()
    {
        $user = Auth::getLoginUser();
        $itemModel = Api::_()->getModel('User\Model\Invite');
        $code = $itemModel->setUser($user)->getUserInviteHash();
        return array(
            'inviteCode' => $code
        );
    
    }

    public function addfriendAction()
    {
        $adapter = $this->params()->fromQuery('service');
        
        $user = Auth::getLoginUser();
    
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
        return array(
            'contacts' => $contacts,
            'service'  => $adapter,
        );
    }

    public function inviteAction()
    {
        return $this->addfriendAction();
    }


    public function corporateAction()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $postData = $request->getPost();
            
            $user = Auth::getLoginUser(); //Could not get user info after form valid

            $form = new \Core\Form\SendEmailForm();
            $form->bind($postData);
            if ($form->isValid()) {
                $item = $form->getData();

                $file = array();
                if($form->getFileTransfer()->isUploaded()) {
                    $form->getFileTransfer()->receive();
                    $files = $form->getFileTransfer()->getFileInfo();
                    $file = $files['attachment'];
                }

                $itemModel = Api::_()->getModel('User\Model\User');
                $user = $itemModel->getUser($user['id']);

                $mail = new \Core\Mail();
                $message = $mail->getMessage();

                $message->setSubject($item['subject'])
                ->setData(array(
                    'user' => $user,
                    'content' => $item['content'],
                ))
                ->setTemplatePath(Api::_()->getModulePath('Epic') . '/view/')
                ->setTemplate('mail/corporate');

                if($file){
                    $message->addAttachment($file['tmp_name']);
                }
                $mail->send();

                return $this->redirect()->toUrl('/account/corporate/');

            } else {
            }
        } else {
        }
    }

    public function notificationAction()
    {
        $user = Auth::getLoginUser();

        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $postData = $request->getPost();
            $itemModel = Api::_()->getModel('Notification\Model\Usersetting');
            
            foreach ($postData['notification_id'] as $key=>$notification_id) {
                $item = array(
                    'user_id' => $user['id'],
                    'notification_id' => $notification_id,
                    'disableNotice' => $postData['disableNotice'][$key],
                    'disableEmail' => $postData['disableEmail'][$key],
                );   
                $itemModel->setItem($item)->removeUsersetting(); 
                $itemModel->setItem($item)->createUsersetting();
            }

            return $this->redirect()->toUrl('/account/notification/');

        } else {
            $userModel = Api::_()->getModel('User\Model\User');
            $user = $userModel->getUser($user['id']);

            $itemModel = Api::_()->getModel('Notification\Model\Notification');
            $items = $itemModel->setItemList(array('noLimit' => true))->getNotificationList();

            $userSettings = array();

            foreach ($items as $key=>$item) {
                $itemModel->setUser($user)
                    ->setNotification($item);

                $setting = $itemModel->getUserSetting();

                $userSettings[$key]['user_id'] = $user['id'];
                $userSettings[$key]['notification_id'] = $item->id;
                foreach ($setting as $columnName=>$column) {
                    $name = str_replace('send', 'disable',$columnName);
                    $userSettings[$key][$name] = $column === 0 ? 1 : 0;
                }
                $userSettings[$key]['Notification'] = $item->toArray();
            } 

            return array(
                'settings' => $userSettings,
            );
        }
    }
}
