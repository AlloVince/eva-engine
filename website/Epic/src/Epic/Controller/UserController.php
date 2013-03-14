<?php
namespace Epic\Controller;

use Eva\Api;
use Eva\Mvc\Controller\ActionController;
use Eva\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Core\Auth;
use Oauth\OauthService;

use Zend\Permissions\Rbac\Rbac;
use Zend\Permissions\Rbac\Role;


class UserController extends ActionController
{
    protected $user;

    protected function checkViewPermission($permission)
    {
        $user = $this->userAction();
        $itemModel = Api::_()->getModel('User\Model\Privacy');
        $privacy = $itemModel->getPrivacy($user['id']);
        if(!$privacy){
            $privacy = array(
                'profile' => 'myGuest',
                'blog' => 'myGuest',
                'album' => 'myGuest',
                'group' => 'myGuest',
                'event' => 'myGuest',
            );
        }


        $visitor = Auth::getLoginUser();
        $itemModel->setUser($user);
        $itemModel->setVisitor($visitor);

        $rbac = new Rbac();
        $roles = \User\Model\Privacy::$privacyRoles;
        foreach($roles as $role){
            $roleKey = $role['roleKey'];
            $rbacRole = new Role($roleKey);
            $permissionKey = array_keys($privacy, $roleKey);
            if(true === is_array($permissionKey)){
                foreach($permissionKey as $key){
                    $rbacRole->addPermission($key);
                }
            } else {
                $rbacRole->addPermission($permissionKey);
            }
            $rbac->addRole($rbacRole);
        }

        $res = false;
        foreach($roles as $role){
            $roleKey = $role['roleKey'];
            $assertionName = 'User\PrivacyAssert\Assert' . ucfirst($roleKey);
            $assertion = new $assertionName;
            $assertion->setUser($user);
            $assertion->setVisitor($visitor);
            $res = $rbac->isGranted($roleKey, $permission, $assertion);

            /*
            p($roleKey);
            p(sprintf("User : %s", $user['id']));
            p(sprintf("Visitor : %s", $visitor['id']));
            p(sprintf("Allow : %s", $res));
            */

            if(true === $res){
                break;
            }
        }
        //p($rbac);
        //p(sprintf("Final : %s", $res));

        return $res;
    }

    public function anonymousAction()
    {
    
    }

    public function userAction()
    {
        if($this->user){
            return $this->user;
        }

        $userId = $this->params('id');
        if(!$userId){
            return array();
        }
        $userModel = Api::_()->getModel('User\Model\User');
        $user = $userModel->getUser($userId);
        if(!$user){
            return array();
        }
        $user = $user->toArray(array(
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
                'FriendsCount' => array(
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
                'User\Item\User::Header' => array(
                    '*',
                    'getThumb()'
                ),
                'Oauth\Item\Accesstoken::Oauth' => array(
                    '*'
                ),
                'Blog\Item\Post::UserPostCount' => array(
                ),
                'Event\Item\EventUser::EventCount' => array(
                ),
            ),
        ));
        return $this->user = $user;
    }

    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getServiceLocator()->get('Application')->getEventManager();
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'setUserToView'), 100);
    }

    public function setUserToView($event)
    {
        $user = $this->userAction();
        $viewModel = $this->getEvent()->getViewModel();
        $viewModel->setVariables(array(
            'user' => $user,
            'viewAsGuest' => 1
        ));
        $viewModelChildren = $viewModel->getChildren();
        foreach($viewModelChildren as $childViewModel){
            $childViewModel->setVariables(array(
                'user' => $user,
                'viewAsGuest' => 1
            ));
        }
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();

        return array(
            'query' => $query,
        ); 
    }

    public function listAction()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();

        $form = new \Epic\Form\UserSearchForm();
        $form->bind($query)->isValid();
        $selectQuery = $form->getData();

        $itemModel = Api::_()->getModel('User\Model\User');
        if(!$selectQuery){
            $selectQuery = array(
                'page' => 1
            );
        }
        $items = $itemModel->setItemList($selectQuery)->getUserList();
        $items = $items->toArray(array(
            'self' => array(
            ),
            'join' => array(
                'Profile' => array(
                    'self' => array(
                        '*'
                    ),
                ),
            ),
            'proxy' => array(
                'User\Item\User::Avatar' => array(
                    '*',
                    'getThumb()'
                ),
                'Event\Item\EventUser::EventCount' => array(
                ),
            ),
        ));
        $paginator = $itemModel->getPaginator();

        $user = Auth::getLoginUser();
        $followList = array();
        if($user) {
            $followModel = Api::_()->getModel('Activity\Model\Follow');
            $followList = $followModel->setUserList($items)->setItemList(array(
                'follower_id' => $user['id']
            ))->getFollowList()->toArray();
        }

        $items = $itemModel->combineList($items, $followList, 'Follow', array('id' => 'user_id'));

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function getAction()
    {
        if(true !== $this->checkViewPermission('profile')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $user = $this->userAction();

        list($items, $paginator) = $this->forward()->dispatch('FeedController', array(
            'action' => 'index',
            'user_id' => $user['id'],
            'author_id' => $user['id'],
        ));

        $viewModel = new ViewModel(array(
            'user' => $user,
            'items' => $items,
            'paginator' => $paginator,
        ));
        $viewModel->setTemplate('epic/home/index');

        return $viewModel;
    }

    public function blogAction()
    {
        if(true !== $this->checkViewPermission('blog')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $page = $this->params()->fromQuery('page', 1);
        $query = array(
            'page' => $page,
        );

        $user = $this->userAction();
        $itemListQuery = array_merge(array(
            'user_id' => $user['id'],
            'order' => 'iddesc',
        ), $query);
        $itemModel = Api::_()->getModel('Blog\Model\Post');
        $items = $itemModel->setItemList($itemListQuery)->getPostList(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                        'getContentHtml()',
                    ),
                ),
            ),
            'proxy' => array(
                'File\Item\File::PostCover' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    ),
                )
            ),
        ));
        $userList = $itemModel->getUserList()->toArray();
        $items = $itemModel->combineList($items, $userList, 'User', array('user_id' => 'id'));

        $paginator = $itemModel->getPaginator();
        return array(
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function postAction()
    {
        if(true !== $this->checkViewPermission('blog')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $id = $this->params('post_id');
        $itemModel = Api::_()->getModel('Blog\Model\Post');
        $item = $itemModel->getPost($id, array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                        'getContentHtml()',
                    ),
                ),
                'Categories' => array(
                ),
            ),
            'proxy' => array(
                'File\Item\File::PostCover' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                )
            ),
        ));
        if(!$item || $item['status'] != 'published'){
            $item = array();
            $this->getResponse()->setStatusCode(404);
        }

        if($item){
            $item['Prev'] = $itemModel->getItem()->getDataClass()->where(array(
                "id < {$item['id']}"
            ))
            ->where(array("status" => "published"))
            ->order('id DESC')->find('one');

            $item['Next'] = $itemModel->getItem()->getDataClass()->where(array(
                "id > {$item['id']}"
            ))
            ->where(array("status" => "published"))
            ->order('id ASC')->find('one');
        }

        $comments = array();
        if($item) {
            $commentModel = Api::_()->getModel('Blog\Model\Comment');
            $comments = $commentModel->setItemList(array(
                'post_id' => $item['id'],
                'noLimit' => true,
            ))->getCommentList(array(
                'self' => array(
                    '*',
                    'getContentHtml()',
                ),
                'proxy' => array(
                    'Blog\Item\Comment::User' => array(
                        'self' => array(
                            '*',
                            'getThumb()',
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
        }

        $view = new ViewModel(array(
            'item' => $item,
            'comments' => $comments,
        ));
        return $view;
    }


    public function albumsAction()
    {
        if(true !== $this->checkViewPermission('album')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $page = $this->params()->fromQuery('page', 1);
        $query = array(
            'page' => $page,
            'rows' => 12,
        );

        $user = $this->userAction();
        $itemListQuery = array_merge(array(
            'user_id' => $user['id'],
            'order' => 'timedesc',
        ), $query);
        $itemModel = Api::_()->getModel('Album\Model\Album');
        $items = $itemModel->setItemList($itemListQuery)->getAlbumList();
        $items = $items->toArray(array(
            'self' => array(
                '*',
            ),  
            'proxy' => array(
                'Album\Item\Album::Cover' => array(
                    '*',
                    'getThumb()'
                ),
            ),
            'join' => array(
                'ImageCount' => array(
                ),
            ),
        ));

        $paginator = $itemModel->getPaginator();
        return array(
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }
    
    public function albumAction()
    {
        if(true !== $this->checkViewPermission('album')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $id = $this->params('album_id');
        
        $itemModel = Api::_()->getModel('Album\Model\Album');
        $item = $itemModel->getAlbum($id, array(
            'self' => array(
                '*',
            ),
            'proxy' => array(
                'Album\Item\Album::Cover' => array(
                    '*',
                    'getThumb()'
                ),
            ),
            'join' => array(
                'Count' => array(
                    '*'
                ),
            ),
        ));
        
        $itemModel = Api::_()->getModel('Album\Model\AlbumFile');

        $query = array(
            'album_id' => $item['id'],
            'noLimit' => true,
        );
        
        $items = $itemModel->setItemList($query)->getAlbumFileList();
        $items->toArray(array(
            'self' => array(
                '*',
            ),
            'proxy' => array(
                'Album\Item\AlbumFile::Image' => array(
                    '*',
                    'getThumb()'
                ),
            ),
        ));

        return array(
            'item' => $item,
            'items' => $items,
        );
    }

    public function groupAction()
    {
        if(true !== $this->checkViewPermission('group')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $id = $this->params('group_id');
        $itemModel = Api::_()->getModel('Group\Model\Group'); 
        $item = $itemModel->getGroup($id, array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                    ),
                ),
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
            ),
        ));

        if(!$item || $item['status'] != 'active'){
            $item = array();
            $this->getResponse()->setStatusCode(404);
        }

        $view = new ViewModel(array(
            'item' => $item,
        ));
        return $view; 
    }

    public function groupsAction()
    {
        if(true !== $this->checkViewPermission('group')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $page = $this->params()->fromQuery('page', 1);
        $query = array(
            'page' => $page,
        );

        $user = $this->userAction();
        $itemListQuery = array_merge(array(
            'user_id' => $user['id'],
            'order' => 'iddesc',
        ), $query);
        $itemModel = Api::_()->getModel('Group\Model\GroupUser');
        $items = $itemModel->setItemList($itemListQuery)->getGroupUserList();
        $items = $items->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Group' => array(
                    'self' => array(
                        '*',
                    ),  
                    'join' => array(
                        'Text' => array(
                            'self' => array(
                                '*',
                            ),
                        ),
                        'File' => array(
                            'self' => array(
                                '*',
                                'getThumb()',
                            )
                        ),
                    ),
                ),
            ),
        ));

        $paginator = $itemModel->getPaginator();
        return array(
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function eventAction()
    {
        if(true !== $this->checkViewPermission('event')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $id = $this->params('event_id');
        $itemModel = Api::_()->getModel('Event\Model\Event'); 
        $item = $itemModel->getEventdata($id, array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                    ),
                ),
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
            ),
        ));

        if(!$item || ($item['eventStatus'] != 'finished' && $item['eventStatus'] != 'active')){
            $item = array();
            $this->getResponse()->setStatusCode(404);
        }

        $view = new ViewModel(array(
            'item' => $item,
        ));
        return $view; 
    }

    public function eventsAction()
    {
        if(true !== $this->checkViewPermission('event')){
            $userId = $this->params('id');
            $this->redirect()->toUrl("/user/$userId/anonymous");
        }

        $page = $this->params()->fromQuery('page', 1);
        $timenode = $this->params()->fromQuery('timenode', '');
        $query = array(
            'page' => $page,
            'timenode' => $timenode,
        );
        $query = $this->eventTimeNodeQuery($query);
        $user = $this->userAction();
        $itemListQuery = array_merge(array(
            'member_id' => $user['id'],
            'order' => 'timedesc',
        ), $query);
        $itemModel = Api::_()->getModel('Event\Model\Event');
        $items = $itemModel->setItemList($itemListQuery)->getEventdataList();
        $items = $items->toArray(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                    ),
                ),
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
            ),
        ));

        $paginator = $itemModel->getPaginator();
        return array(
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }
    
    protected function eventTimeNodeQuery($query)
    {
        if (!isset($query['timenode'])) {
            return $query;
        }
            
        $nowTime = \Eva\Date\Date::getNow();

        switch ($query['timenode']) {
        case 'upcoming':
            $startTime = \Eva\Date\Date::getFuture(3600 * 24 * 1, $nowTime, 'Y-m-d H:i:s');
            $query['afterStartDay'] = $startTime;
            break;  
        case 'ongoing':
            $endTime = \Eva\Date\Date::getFuture(3600 * 24 * 1, $nowTime, 'Y-m-d H:i:s');
            $startTime = \Eva\Date\Date::getBefore(3600 * 24 * 1, $nowTime, 'Y-m-d H:i:s');
            $query['afterStartDay'] = $startTime;
            $query['beforeStartDay'] = $endTime;
            break;  
        case 'finished':
            $endTime = \Eva\Date\Date::getBefore(3600 * 24 * 1, $nowTime, 'Y-m-d H:i:s');
            $query['beforeStartDay'] = $endTime;
            break;  
        default:
            return $query;
        }
        
        return $query;
    }

    public function registerAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $item = $request->getPost();

            $oauth = new \Oauth\OauthService();
            $accessToken = array();
            if($oauth->getStorage()->getAccessToken()) {
                $oauth->setServiceLocator($this->getServiceLocator());
                $oauth->initByAccessToken();
                $accessToken = $oauth->getAdapter()->getAccessToken();
            }

            $form = $accessToken ? new \User\Form\QuickRegisterForm : new \Epic\Form\RegisterForm();
            $form->bind($item);
            if ($form->isValid()) {
                $callback = $this->params()->fromPost('callback');
                $callback = $callback ? $callback : '/?reg=1';

                $item = $form->getData();
                $itemModel = Api::_()->getModel('User\Model\Register');
                $itemModel->setItem($item)->register();

                $userItem = $itemModel->getItem();
                $codeItem = $itemModel->getItem('User\Item\Code');
                $mail = new \Core\Mail();
                $mail->getMessage()
                    ->setSubject("Please Confirm Your Email Address")
                    ->setData(array(
                        'user' => $userItem,
                        'code' => $codeItem,
                    ))
                    ->setTo($userItem->email, $userItem->userName)
                    ->setTemplatePath(Api::_()->getModulePath('Epic') . '/view/')
                    ->setTemplate('mail/active');
                $mail->send();

                $this->redirect()->toUrl($callback);
            } else {
            }
            return array(
                'token' => $accessToken,
                'form' => $form,
                'item' => $item,
            );
        } else {
            return array(
                'item' => $this->getRequest()->getQuery()
            );
        }
    }
}
