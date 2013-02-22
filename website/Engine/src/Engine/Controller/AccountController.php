<?php
namespace Engine\Controller;

use Eva\Api;
use Eva\Mvc\Controller\ActionController;
use Eva\View\Model\ViewModel;
use Core\Auth;
use Engine\Form;


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
                $itemModel = Api::_()->getModel('User\Model\User');
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
        $viewModel->setTemplate('engine/account/setting');
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
        $viewModel->setTemplate('engine/account/setting');
        $viewModel->setVariables(array(
            'pwForm' => $form
        ));
        return $viewModel;
    }
}
