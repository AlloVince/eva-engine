<?php
namespace User\Admin\Controller;

use User\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class UserController extends RestfulModuleController
{
    protected $renders = array(
        'restPutUser' => 'user/get',    
        'restPostUser' => 'user/get',    
        'restDeleteUser' => 'remove/get',    
    );

    public function restIndexUser()
    {
        $request = $this->getRequest();

        $query = $request->getQuery();

        $form = Api::_()->getForm('User\Form\UserSearchForm');
        $selectQuery = $form->fieldsMap($query, true);

        $itemModel = Api::_()->getModelService('User\Model\User');
        if(!$selectQuery){
            $selectQuery = array(
                'page' => 1
            );
        }
        $items = $itemModel->setItemList($selectQuery)->getUserList();
        //p($items[0]->join('Profile')->self(array('*'))->site);
        $items = $items->toArray(array(
            'self' => array(
                '*',
                'userName',
            ),
            'join' => array(
                'Profile' => array(
                    //'*',
                    'site',
                    'birthday',
                    'phoneMobile',
                ),
                'Account' => array('*'),
            ),
        ));
        $paginator = $itemModel->getPaginator();

        return array(
            'form' => $form,
            'users' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetUser()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $itemModel = Api::_()->getModelService('User\Model\User');
        $item = $itemModel->getUser($id);

        //p($item->self(array('*'))->toArray());

        //$item = $itemModel->getUser(1);
        //p($item->self(array('*'))->userName);
        
        //$credits = $item->join('Account')->self(array('*'))->credits;
        //p($credits);

        $item = $item->toArray(array(
            'self' => array(
                '*',
                'getRegisterIp()',
                'getFullName()',
            ),
            'join' => array(
                'Profile' => array(
                    //'*',
                    'site',
                    'birthday',
                    'phoneMobile',
                ),
                'Account' => array('*'),
                'MyFriends' => array(
                    'self' => array(
                        'userName',
                    ),
                    'join' => array(
                        'Profile' => array()
                    )
                ),
                'Oauth' => array(
                    //'appExt'
                ),
            ),
            'proxy' => array(
                'Blog\Item\Post::UserPosts' => array(
                    'self' => array('*'),
                    'join' => array(
                        'Text' => array('*'),
                        'Comments' => array(
                            'self' => array(
                                '*'
                            ),
                            'proxy' => array(
                                'User\Item\User::CommentUser' => array(
                                    'self' => array(
                                        'userName'
                                    )
                                )
                            ),
                        )
                    ),
                ),
            ) 
        ));

        if(!$item){
            //Add redirect
        }
        //p($item);

        return array(
            'user' => $item,
            'flashMessenger' => $this->flashMessenger()->getMessages(),
        );
    }

    public function restPostUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $form = new Form\UserForm();
        $subForms = array(
            'Profile' => array('User\Form\ProfileForm'),
            'Account' => array('User\Form\AccountForm'),
        );
        $form->setSubforms($subForms)
             ->init()
             ->setData($postData)
             ->enableFilters();

        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\User');
            $itemId = $itemModel->setItem($postData)->createUser();
            $this->flashMessenger()->addMessage('item-create-succeed');
            $this->redirect()->toUrl('/admin/user/' . $itemId);

        } else {
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function restPutUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $form = new Form\UserEditForm();
        $subForms = array(
            'Profile' => array('User\Form\ProfileForm'),
            'Account' => array('User\Form\AccountForm'),
        );
        $form->setSubforms($subForms)
             ->init()
             ->setData($postData)
             ->enableFilters();

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\User');

            $itemId = $itemModel->setItem($postData)->saveUser();

            $this->flashMessenger()->addMessage('item-edit-succeed');
            $this->redirect()->toUrl('/admin/user/' . $postData['id']);
        } else {
            //$this->flashMessenger()->addMessage('');
            //$flashMesseger = array('post-edit-failed');
        }

        return array(
            'form' => $form,
            'user' => $postData,
            //'flashMessenger' => $flashMesseger
        );
    }

    public function restDeleteUser()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $callback = $request->getPost()->get('callback');

        $form = new Form\UserDeleteForm();
        $form->enableFilters()->setData($postData);
        if ($form->isValid()) {

            $postData = $form->getData();
            $itemModel = Api::_()->getModelService('User\Model\User');
            $itemModel->setItem(array(
                'id' => $postData['id']
            ))->removeUser();

            if($callback){
                $this->redirect()->toUrl($callback);
            }

        } else {
            return array(
                'item' => $postData,
            );
        }
    }
}

