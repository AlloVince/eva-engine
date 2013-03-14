<?php
namespace Epic\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;
use Core\Auth;
use Group\Form;
use Epic\Exception;

class GroupController extends ActionController
{
    protected $group;
    
    protected $post;
    
    protected $eventData;
   
    public function indexAction()
    {
        return $this->listAction();
    }

    public function listAction()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();

        $form = new \Epic\Form\GroupSearchForm();
        $form->bind($query)->isValid();
        $selectQuery = $form->getData();

        $itemModel = Api::_()->getModel('Group\Model\Group');
        if(!$selectQuery){
            $selectQuery = array(
                'page' => 1
            );
        }
        $selectQuery['status'] = 'active';
        $items = $itemModel->setItemList($selectQuery)->getGroupList();
        $items = $items->toArray(array(
            'self' => array(
            ),
            'join' => array(
                'Count' => array(
                    '*',
                ),
            ),
        ));
        $paginator = $itemModel->getPaginator();

        $user = Auth::getLoginUser();
        $joinList = array();
        if($user) {
            $joinModel = Api::_()->getModel('Group\Model\GroupUser');
            $joinList = $joinModel->setItemList(array(
                'user_id' => $user['id']
            ))->getGroupUserList()->toArray();
        }

        $items = $itemModel->combineList($items, $joinList, 'Join', array('id' => 'group_id'));


        //Public User Area
        $this->forward()->dispatch('UserController', array(
            'action' => 'user',
            'id' => $user['id'],
        ));
        
        $categoryModel = Api::_()->getModel('Group\Model\Category');
        $categories = $categoryModel->setItemList(array('noLimit' => true))->getCategoryList();
        $categories = $categories->toArray();
        
        if ($query['category']) {
            $category = $categoryModel->getCategory($query['category']);
        } else {
            $category = array(
                'id' => '',
                'urlName' => '',
                'categoryName' => 'Hot',
            );
        }

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'categories' => $categories,
            'category' => $category,
            'paginator' => $paginator,
        );   
    }

    public function getAction()
    {
        $id = $this->params('id');

        list($item, $members) = $this->groupAction();
        list($posts, $paginator) = $this->blogAction($item['id']);

        $view = new ViewModel(array(
            'item' => $item,
            'members' => $members,
            'posts' => $posts,
            'paginator' => $paginator,
        ));
        return $view; 
    }
    
    public function categoryAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $categoryModel = Api::_()->getModel('Group\Model\Category');
        $category = $categoryModel->getCategory($id);
        
        $query = $this->getRequest()->getQuery();
        $form = new \Blog\Form\PostSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return new JsonModel(array(
                'form' => $form,
                'items' => array(),
            ));
        }
        
        $inGroup = $this->params()->fromQuery('inGroup');
        $groupCategrory = $category['id'];
        $rows = $this->params()->fromQuery('rows',25);

        $query['inGroup'] = true;
        $query['groupCategory'] = $groupCategrory;
        $query['rows'] = $rows;

        $itemModel = Api::_()->getModel('Group\Model\Post'); 
        $items = $itemModel->setItemList($query)->getPostList(array(
            'self' => array(
                '*', 
            ),
            'join' => array(
                'Group' => array(
                    '*'
                ),
                'Text' => array(
                    'self' => array(
                        '*',
                        'getPreview()',
                        'getContentHtml()',
                    )
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

        if (count($items) > 0) {
            foreach ($items as $key=>$item) {
                if (isset($item['Group']) && count($item['Group']) > 0) {
                    unset($items[$key]['Group'][0]);
                    $items[$key]['Group'] = $item['Group'][0];
                } else {
                    unset($items[$key]['Group']);
                }
            }
        }

        if(Api::_()->isModuleLoaded('User')){
            $userList = array();
            $userList = $itemModel->getUserList(array(
                'columns' => array(
                    'id',
                    'userName',
                    'email',
                    'avatar_id',
                ),
            ))->toArray(array(
                'proxy' => array(
                    'User\Item\User::Avatar' => array(
                        '*',
                        'getThumb()'
                    ),
                ),
            ));
            $items = $itemModel->combineList($items, $userList, 'User', array('user_id' => 'id'));
        }
        
        $user = Auth::getLoginUser(); 
        //Public User Area
        $this->forward()->dispatch('UserController', array(
            'action' => 'user',
            'id' => $user['id'],
        ));

        return array(
            'form' => $form,
            'items' => $items,
            'query' => $query,
            'category' => $category,
        );   
    }

    public function groupAction()
    {
        if($this->group){
            return $this->group;
        }   

        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        if(!$id){
            return array();
        }

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
                'Category' => array(
                    '*'
                ),
                'Count' => array(
                    '*',
                ),
                'PostCount' => array(
                ),
                'Tags' => array(
                    '*'
                ),
            ),
        ));

        if(!$item || $item['status'] != 'active'){
            $item = array();
            throw new Exception\PageNotFoundException('Page not found');
        }

        $user = Auth::getLoginUser(); 
        //Public User Area
        $this->forward()->dispatch('UserController', array(
            'action' => 'user',
            'id' => $user['id'],
        ));

        if($user) {
            $joinModel = Api::_()->getModel('Group\Model\GroupUser');
            $joinList = $joinModel->setItemList(array(
                'group_id' => $item['id'],
                'user_id' => $user['id']
            ))->getGroupUserList()->toArray();

            if (count($joinList) > 0) {
                $item['Join'] = $joinList[0];
            }
        }

        $memberModel = Api::_()->getModel('Group\Model\GroupUser'); 
        $members = $memberModel->setItemList(array('group_id' => $item['id'], 'noLimit' => true))->getGroupUserList();
        $members = $members->toArray(
            array(
                'self' => array(
                    '*',
                ),
                'join' => array(
                    'User' => array(
                        'self' => array(
                            '*'
                        ),
                        'proxy' => array(
                            'User\Item\User::Avatar' => array(
                                '*',
                                'getThumb()'
                            ),
                        ),
                    ),
                ),

            )
        );

        $this->group = $item;

        return array($item, $members);
    }

    public function removeAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData = $this->params()->fromPost();
            $callback = $this->params()->fromPost('callback');

            $form = new \Group\Form\GroupDeleteForm();
            $form->bind($postData);
            if ($form->isValid()) {

                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Group\Model\Group');
                $itemModel->setItem($postData)->removeGroup();
                $callback = $callback ? $callback : '/my/group/';
                $this->redirect()->toUrl($callback);

            } else {
                return array(
                    'post' => $postData,
                );
            }

        } else {
            $id = $this->params('id');
            $itemModel = Api::_()->getModel('Group\Model\Group');
            $item = $itemModel->getGroup($id)->toArray();

            return array(
                'callback' => $this->params()->fromQuery('callback'),
                'item' => $item,
            );

        }

    }

    public function createAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return;
        }

        $postData = $request->getPost();
        $callback = $this->params()->fromPost('callback');
        $form = new \Epic\Form\GroupCreateForm();
        $form->useSubFormGroup()
            ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $postData['status'] = 'active';
            $itemModel = Api::_()->getModel('Group\Model\Group');
            $groupId = $itemModel->setItem($postData)->createGroup();
            $callback = $callback ? $callback : '/my/group/';
            $this->redirect()->toUrl($callback);
        } else {
           
        }

        return array(
            'form' => $form,
            'post' => $postData,
        );
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/group/create');
        if ($request->isPost()) {
            $postData = $request->getPost();
            $callback = $this->params()->fromPost('callback');
            $form = new \Epic\Form\GroupEditForm();
            $form->useSubFormGroup()
                ->bind($postData);

            if ($form->isValid()) {
                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Group\Model\Group');
                $groupId = $itemModel->setItem($postData)->saveGroup();
                $callback = $callback ? $callback : '/my/group/';
                $this->redirect()->toUrl($callback);

            } else {
            }

            $viewModel->setVariables(array(
                'form' => $form,
                'item' => $postData,
            ));
        } else {
            $id = $this->params('id');
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
                    'Category' => array(
                        '*'
                    ),
                    'Tags' => array(
                        '*'
                    ),
                ),
            ));
            if(isset($item['GroupFile'][0])){
                $item['GroupFile'] = $item['GroupFile'][0];
            }

            $viewModel->setVariables(array(
                'item' => $item,
            ));
        }

        return $viewModel;
    }

    public function albumAction()
    {
        list($item, $members) = $this->groupAction();
        $groupId = $item['id'];

        $viewModel = new ViewModel();

        $page = $this->params()->fromQuery('page', 1);
        $rows = $this->params()->fromQuery('rows', 16);
        $order = $this->params()->fromQuery('order', 'iddesc');

        $eventModel = Api::_()->getModel('Group\Model\GroupEvent');
        $events = $eventModel->setItemList(array('group_id' => $groupId, 'noLimit' => true))->getGroupEventList(array(
            'self' => array(
            ),
        ));

        $albums = array();
        $paginator = array();

        if ($events) {
            $eventIdArray = array();
            foreach ($events as $event) {
                $eventIdArray[] = $event['event_id'];
            }
        }

        if (!$eventIdArray) {
            $viewModel->setVariables(array(
                'item' => $item,
                'members' => $members,
                'items' => $albums,
                'paginator' => $paginator,
                'query' => $this->params()->fromQuery(),
            ));

            return $viewModel;  
        }

        $itemModel = Api::_()->getModel('Event\Model\Album'); 
        $albums = $itemModel->setItemList(array(
            'inEvent' => true,
            'event_id' => $eventIdArray,
            'page' => $page,
            'rows' => $rows,
            'order' => $order
        ))->getAlbumList(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'ImageCount' => array(
                ),
            ),
            'proxy' => array(
                'Album\Item\Album::Cover' => array(
                    '*',
                    'getThumb()'
                ),
            ),
        )); 

        $paginator = $itemModel->getPaginator();


        $viewModel->setVariables(array(
            'item' => $item,
            'members' => $members,
            'items' => $albums,
            'paginator' => $paginator,
            'query' => $this->params()->fromQuery(),
        ));

        return $viewModel; 
    }


    public function albumGetAction()
    {
        $request = $this->getRequest();
        $albumId = $this->params('album_id');
        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/group/album-get');
        list($item, $members) = $this->groupAction();

        $itemModel = Api::_()->getModel('Album\Model\Album');
        $album = $itemModel->getAlbum($albumId);

        $itemModel = Api::_()->getModel('Album\Model\AlbumFile');

        $query = array(
            'album_id' => $album['id'],
            'noLimit' => true
        );

        $items = $itemModel->setItemList($query)->getAlbumFileList();
        $paginator = $itemModel->getPaginator();
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

        $items = $items->toArray();

        $viewModel->setVariables(array(
            'item' => $item,
            'items' => $items,
            'members' => $members,
        ));

        return $viewModel;
    }

    public function blogAction($groupId)
    {
        if (!$groupId) {
            return array();
        }

        $page = $this->params()->fromQuery('page', 1);
        $rows = $this->params()->fromQuery('rows', 20);
        $order = $this->params()->fromQuery('order', 'timedesc');

        $this->changeViewModel('json');

        $itemModel = Api::_()->getModel('Group\Model\Post'); 
        $items = $itemModel->setItemList(array(
            'inGroup' => true,
            'group_id' => $groupId,
            'page' => $page,
            'rows' => $rows,
            'order' => $order
        ))->getPostList(array(
            'self' => array(
                '*', 
            ),
            'join' => array(
                'Group' => array(
                    '*'
                ),
            ),
        ));

        if (count($items) > 0) {
            foreach ($items as $key=>$item) {
                if (count($item['Group']) > 0) {
                    unset($items[$key]['File'][0]);
                    $items[$key]['Group'] = $item['Group'][0];
                } else {
                    unset($items[$key]['Group']);
                }
            }
        }

        $paginator = $itemModel->getPaginator();
        $paginator = $paginator ? $paginator : null;

        if(Api::_()->isModuleLoaded('User')){
            $userList = array();
            $userList = $itemModel->getUserList(array(
                'columns' => array(
                    'id',
                    'userName',
                    'email',
                ),
            ))->toArray(array(
                'self' => array(
                    'getEmailHash()',
                ),

            ));
            $items = $itemModel->combineList($items, $userList, 'User', array('user_id' => 'id'));
        }

        return array($items, $paginator); 

    }

    public function postCreateAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();

        list($item, $members) = $this->groupAction();

        return array(
            'item' => $item,
            'members' => $members,
        );   
    }

    public function postEditAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/group/post-create');

        $postId = $this->getEvent()->getRouteMatch()->getParam('post_id');

        $postView = $this->forward()->dispatch('BlogController', array(
            'action' => 'edit',
            'id' => $postId,
        ));

        list($item, $members) = $this->groupAction();

        $viewModel->setVariables(array(
            'item' => $item,
            'post' => $postView->item,
            'members' => $members,
        ));

        return $viewModel;
    }

    public function postGetAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();

        $postId = $this->getEvent()->getRouteMatch()->getParam('post_id');
        
        $user = Auth::getLoginUser(); 
        
        $postView = $this->forward()->dispatch('UserController', array(
            'action' => 'post',
            'post_id' => $postId,
            'id' => $user['id']
        ));

        list($item, $members) = $this->groupAction();

        $viewModel->setVariables(array(
            'item' => $item,
            'post' => $postView->item,
            'comments' => $postView->comments,
            'members' => $members,
        ));

        return $viewModel;
    }

    public function sendmailAction()
    {
        list($item, $members) = $this->groupAction();

        $viewModel = new ViewModel();
        
        $eventId = $this->params()->fromQuery('event');

        if ($eventId) {
            $eventView = $this->forward()->dispatch('EventController', array(
                'action' => 'get',
                'id' => $eventId,
            ));
        }

        $viewModel->setVariables(array(
            'item' => $item,
            'event' => isset($eventView) ? $eventView->item : null,
            'members' => $members,
        ));

        $user = Auth::getLoginUser(); //Could not get user info after form valid

        if ($user['id'] != $item['user_id']) {
            throw new Exception\InvalidArgumentException('User id not match');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();

            $userIds = $postData['user_id'];

            if (!$postData['user_id']) {
                throw new Exception\InvalidArgumentException('No user id');
            }



            $form = new \Core\Form\SendEmailForm();
            $form->bind($postData);
            if ($form->isValid()) {
                $data = $form->getData();
                $file = array();
                if($form->getFileTransfer()->isUploaded()) {
                    $form->getFileTransfer()->receive();
                    $files = $form->getFileTransfer()->getFileInfo();
                    $file = $files['attachment'];
                }

                $userModel = Api::_()->getModel('User\Model\User');
                $users = $userModel->setItemList(array(
                    'noLimit' => true,
                    'id' => $userIds,
                ))->getUserList()->toArray();

                $mail = new \Core\Mail();
                $message = $mail->getMessage();

                foreach ($users as $user) {
                    $message->addBcc($user['email']);
                }

                $message->setSubject($data['subject'])
                    ->setBody($data['content']);

                if($file['tmp_name']){
                    $message->addAttachment($file['tmp_name']);
                }
                $mail->send();

                return $this->redirect()->toUrl('/group/' . $item['groupKey']);

            } else {
            }
        } 

        return $viewModel; 
    }

    public function eventAction()
    {
        list($item, $members) = $this->groupAction();
        $groupId = $item['id'];

        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/group/event-list');

        $page = $this->params()->fromQuery('page', 1);
        $rows = $this->params()->fromQuery('rows', 10);
        $order = $this->params()->fromQuery('order', 'timedesc');

        $eventView = $this->forward()->dispatch('EventController', array(
            'action' => 'list',
            'group_id' => $groupId,
            'order' => $order,
        )); 

        $viewModel->setVariables(array(
            'item' => $item,
            'members' => $members,
            'items' => $eventView->items,
            'paginator' => $eventView->paginator,
            'query' => $eventView->query,
        ));

        return $viewModel; 
    }

    public function eventCreateAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();

        list($item, $members) = $this->groupAction();

        return array(
            'item' => $item,
            'members' => $members,
        );   
    }

    public function eventEditAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('epic/group/event-create');

        $eventId = $this->getEvent()->getRouteMatch()->getParam('event_id');

        $eventView = $this->forward()->dispatch('EventController', array(
            'action' => 'get',
            'id' => $eventId,
        ));
        /*
        $viewModel->addChild($eventView, 'event');
         */
        list($item, $members) = $this->groupAction();

        $viewModel->setVariables(array(
            'item' => $item,
            'event' => $eventView->item,
            'members' => $members,
        ));

        return $viewModel;
    }

    public function eventGetAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $eventId = $this->getEvent()->getRouteMatch()->getParam('event_id');

        $eventView = $this->forward()->dispatch('EventController', array(
            'action' => 'get',
            'id' => $eventId,
        ));
        /*
        $viewModel->addChild($eventView, 'event');
         */
        list($item, $members) = $this->groupAction();
        $viewModel->setVariables(array(
            'item' => $item,
            'event' => $eventView->item,
            'items' => $eventView->items,
            'images' => $eventView->images,
            'eventMembers' => $eventView->members,
            'members' => $members,
            'paginator' => $eventView->paginator,
        ));


        return $viewModel;
    }

    public function postAction()
    {
        $itemModel = Api::_()->getModel('Group\Model\Post'); 
        $items = $itemModel->setItemList(array(
            'inGroup' => true,
            'group_id' => 1,
            'order' => 'commentdesc'
        ))->getPostList(array(
            'self' => array(
                '*', 
            )
        ));
        $paginator = $itemModel->getPaginator();
        $paginator = $paginator ? $paginator->toArray() : null;

        if(Api::_()->isModuleLoaded('User')){
            $userList = array();
            $userList = $itemModel->getUserList(array(
                'columns' => array(
                    'id',
                    'userName',
                    'email',
                ),
            ))->toArray(array(
                'self' => array(
                    'getEmailHash()',
                ),
            ));
            $items = $itemModel->combineList($items, $userList, 'User', array('user_id' => 'id'));
        }

        return array(
            'items' => $items
        );
    }
}
