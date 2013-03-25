<?php
namespace Epic\Controller;

use Epic\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

class DataController extends RestfulModuleController
{
    protected $renders = array(
        'restIndexGroup' => 'blank',    
    );

    public function geoAction()
    {
        $this->changeViewModel('json');

        $city = 'unkown';
        $geoData = EVA_ROOT_PATH . '/data/databases/GeoLiteCity.dat';

        if($this->cookie()->crypt(false)->read('city')){
            $city = $this->cookie()->crypt(false)->read('city');
        } elseif(file_exists($geoData)) {
            new \GeoIP();
            $ip = $_SERVER['REMOTE_ADDR'];
            $gi = geoip_open($geoData, GEOIP_STANDARD);
            $record = geoip_record_by_addr($gi, $ip);
            if(isset($record->city)){
                $city = $record->city;
            }
            geoip_close($gi);
        }
        return new JsonModel(array(
            'item' => $city
        ));
    }

    public function myAction()
    {
        $this->changeViewModel('json');
        $item = \Core\Auth::getLoginUser();
        $itemModel = Api::_()->getModel('User\Model\User');
        $itemRoles = $itemModel->getUser($item['id'], array(
            'self' => array(
            ),
            'join' => array(
                'Roles' => array(
                    'self' => array(
                        '*'
                    ),
                ),
            ),
        ));

        $now = \Eva\Date\Date::getNow();

        if(isset($itemRoles['Roles'])){
            foreach($itemRoles['Roles'] as $role){
                if($role['RoleUser']['status'] == 'active'){
                    if ($role['roleKey'] == 'PAID_MEMBER' && $role['RoleUser']['expiredTime'] && $role['RoleUser']['expiredTime'] < $now){
                        continue;
                    }
                    
                    $item['Roles'][] = $role['roleKey'];
                }
            }
            //$item['Roles'] = $itemRoles['Roles'];
        }
        if(!$item) {
            return new JsonModel(array(
                'item' => null
            ));
        }
        return new JsonModel(array(
            'item' => $item,
        ));
    }

    public function newsletterAction()
    {
        $this->changeViewModel('json');

        $user = \Core\Auth::getLoginUser();
        if(!$user) {
            return new JsonModel(array(
                'item' => null
            ));
        }
        $itemModel = Api::_()->getModel('Core\Model\Newsletter');
        $item = $itemModel->getNewsletter($user['id'])->toArray();
        return new JsonModel(array(
            'item' => $item,
        ));
    }

    public function blogAction()
    {
        $this->changeViewModel('json');
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
        $groupId = $this->params()->fromQuery('group_id');
        $inGroup = $this->params()->fromQuery('inGroup');
        $groupCategrory = $this->params()->fromQuery('groupCategory');
        $rows = $this->params()->fromQuery('rows');

        if ($groupId || $inGroup) { 
            $query['inGroup'] = true;
            $query['group_id'] = $groupId;
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
                        )
                    ),
                ),
            ));
        } else {
            $itemModel = Api::_()->getModel('Blog\Model\Post');
            $items = $itemModel->setItemList($query)->getPostList(array(
                'join' => array(
                    'Text' => array(
                        'self' => array(
                            '*',
                            'getPreview()',
                        )
                    ),
                )
            ));
        }

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

        $paginator = $itemModel->getPaginator();
        $paginator = $paginator ? $paginator->toArray() : null;

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

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    public function isfriendAction()
    {
        $this->changeViewModel('json');

        $user = \Core\Auth::getLoginUser();
        if(!$user) {
            return new JsonModel(array(
                'item' => null
            ));
        }
        $selectQuery = array(
            'friend_id' => $this->params()->fromQuery('user_id'),
            'user_id' => $user['id'],
        );
        $itemModel = Api::_()->getModel('User\Model\Friend');
        $item = $itemModel->setItemList($selectQuery)->getFriendList();
        $item = $item ? $item->toArray() : array();
        return new JsonModel(array(
            'item' => $item,
        ));
    }

    public function isfollowerAction()
    {
        $this->changeViewModel('json');

        $user = \Core\Auth::getLoginUser();
        if(!$user) {
            return new JsonModel(array(
                'item' => null
            ));
        }
        $selectQuery = array(
            'user_id' => $this->params()->fromQuery('user_id'),
            'follower_id' => $user['id'],
        );
        $itemModel = Api::_()->getModel('Activity\Model\Follow');
        $item = $itemModel->setItemList($selectQuery)->getFollowList();
        $item = $item ? $item->toArray() : array();

        return new JsonModel(array(
            'item' => $item,
        ));
    }

    public function friendAction()
    {
        $this->changeViewModel('json');
        $selectQuery = array(
            'user_id' => $this->params()->fromQuery('user_id'),
            'relationshipStatus' => $this->params()->fromQuery('status', 'approved'),
            'page' => $this->params()->fromQuery('page', 1),
            'rows' => $this->params()->fromQuery('rows', 16),
        );
        $itemModel = Api::_()->getModel('User\Model\Friend');
        $items = $itemModel->setItemList($selectQuery)->getFriendList()->toArray(array(
            'self' => array(
            ),
            'join' => array(
                'User' => array(
                    'self' => array(
                        'id',
                        'userName',
                        'email',
                        'avatar_id',
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
        $paginator = $paginator ? $paginator->toArray() : null;

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    public function eventAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $form = new \Epic\Form\EventSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $groupId = $this->params()->fromQuery('group_id');
        $inGroup = $this->params()->fromQuery('inGroup');

        if ($groupId || $inGroup) { 
            $itemModel = Api::_()->getModel('Group\Model\Event'); 
            $query['inGroup'] = true;
            $query['group_id'] = $groupId;

            $items = $itemModel->setItemList($query)->getEventdataList(array(
                'self' => array(
                    '*'
                ),
                'join' => array(
                    'Count' => array(
                        '*',
                    ),
                    'File' => array(
                        'self' => array(
                            '*',
                            'getThumb()',
                        )
                    ),
                    'Group' => array(
                        '*'
                    ),
                ), 
            ));

        } else {
            $itemModel = Api::_()->getModel('Event\Model\Event');
            $items = $itemModel->setItemList($query)->getEventdataList();
            $items = $items->toArray(array(
                'self' => array(
                    '*'
                ),
                'join' => array(
                    'Count' => array(
                        '*',
                    ),
                    'File' => array(
                        'self' => array(
                            '*',
                            'getThumb()',
                        )
                    ),
                ), 
            ));
        }

        if (count($items) > 0) {
            foreach ($items as $key=>$item) {
                if (isset($item['File']) && count($item['File']) > 0) {
                    unset($items[$key]['File'][0]);
                    $items[$key]['File'] = $item['File'][0];
                } else {
                    unset($items[$key]['File']);
                }

                if (isset($item['Group']) && count($item['Group']) > 0) {
                    unset($items[$key]['Group'][0]);
                    $items[$key]['Group'] = $item['Group'][0];
                } else {
                    unset($items[$key]['Group']);
                }
            }
        }

        $paginator = $itemModel->getPaginator();
        $paginator = $paginator ? $paginator->toArray() : null;

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
                'self' => array(
                    'getEmailHash()',
                ),
                'proxy' => array(
                    'User\Item\User::Avatar' => array(
                        '*',
                        'getThumb()'
                    ),
                ),
            ));
            $items = $itemModel->combineList($items, $userList, 'User', array('user_id' => 'id'));
        }

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    public function groupAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $form = new \Epic\Form\GroupSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }

        $itemModel = Api::_()->getModel('Group\Model\Group');
        $items = $itemModel->setItemList($query)->getGroupList();
        $items = $items->toArray(array(
            'self' => array(
            ),
            'join' => array(
                'Count' => array(
                    '*',
                ),
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
                'PostCount' => array(
                ),
            ), 
        ));

        if (count($items) > 0) {
            foreach ($items as $key=>$item) {
                if (count($item['File']) > 0) {
                    unset($items[$key]['File'][0]);
                    $items[$key]['File'] = $item['File'][0];
                } else {
                    unset($items[$key]['File']);
                }
            }
        }

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
                'proxy' => array(
                    'User\Item\User::Avatar' => array(
                        '*',
                        'getThumb()'
                    ),
                ),
            ));
            $items = $itemModel->combineList($items, $userList, 'User', array('user_id' => 'id'));
        }

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    public function albumAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $form = new \Epic\Form\AlbumSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'form' => $form,
                'items' => array(),
            );
        }
        $groupId = $this->params()->fromQuery('group_id');

        if ($groupId) { 
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
                return new JsonModel(array(
                    'items' => $albums,
                    'paginator' => $paginator,
                ));
            }

            $itemModel = Api::_()->getModel('Event\Model\Album'); 
            $query['inEvent'] = true;
            $query['event_id'] = $eventIdArray;
            $items = $itemModel->setItemList($query)->getAlbumList(array(
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
            $paginator = $paginator ? $paginator->toArray() : null;
        } else {
            $itemModel = Api::_()->getModel('Album\Model\Album');
            $items = $itemModel->setItemList($query)->getAlbumList();
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
                    'Count' => array(
                        '*'
                    ),
                ),
            ));

            $paginator = $itemModel->getPaginator();
            $paginator = $paginator ? $paginator->toArray() : null;
        }
        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    public function eventuserAction()
    {
        $this->changeViewModel('json');
        $itemModel = Api::_()->getModel('Event\Model\User');
        $query = $this->getRequest()->getQuery();
        $items = $itemModel->setItemList(array(
            'inEvent' => 1,
            'eventRole' => 'admin',
            'role' => $query['role'],
            'excludeId' => 1,
            'order' => 'eventcountdesc'
        ))->getUserList();
        $items = $items->toArray(array(
            'self' => array(
                'getEmailHash()',
            ),
            'proxy' => array(
                'User\Item\User::Avatar' => array(
                    '*',
                    'getThumb()'
                ),
            ),
        ));

        $paginator = $itemModel->getPaginator();
        $paginator = $paginator ? $paginator->toArray() : null;

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    public function userAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();

        $form = new \Epic\Form\UserSearchForm();
        $form->bind($query)->isValid();
        $selectQuery = $form->getData();

        $itemModel = Api::_()->getModel('User\Model\User');
        if(!$selectQuery){
            $selectQuery = array(
                'page' => 1
            );
        }
        $selectQuery['excludeStatus'] = 'deleted';
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
            ),
        ));
        $paginator = $itemModel->getPaginator();
        $paginator = $paginator ? $paginator->toArray() : null;

        return new JsonModel(array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    public function eventjoinAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $id = $query['id'];
        
        $user = \Core\Auth::getLoginUser(); 

        if(!$id || !$user){
            return new JsonModel(array(
                'items' => array(),
            ));
        }
        
        $idArray = explode('-',$id);

        $query = array(
            'id' => $idArray,
            'noLimit' => true,
        );
        
        $itemModel = Api::_()->getModel('Event\Model\Event');
        $items = $itemModel->setItemList($query)->getEventdataList();
        $items = $items->toArray(array(
            'self' => array(
                'id',
                'user_id',
                'memberEnable',
                'memberLimit',
                'startDay',
                'startTime',
                'endDay',
                'endTime',
            ),
            'join' => array(
                'Count' => array(
                    '*',
                ),
            ), 
        ));
        
        $joinList = array();
        if($user) {
            $joinModel = Api::_()->getModel('Event\Model\EventUser');
            $joinList = $joinModel->setItemList(array(
                'event_id' => $idArray,
                'user_id' => $user['id']
            ))->getEventUserList()->toArray();
        }
        $items = $itemModel->combineList($items, $joinList, 'Join', array('id' => 'event_id'));

        if (count($items) > 0) {
            $res = array();
            $nowTime = \Eva\Date\Date::getNow();
            foreach ($items as $key=>$item) {
                $res[$key] = array(
                    'id' => $item['id'],
                    'user_id' => $item['user_id'],
                    'memberEnable' => $item['memberEnable'],
                    'memberLimit' => $item['memberLimit'],
                    'memberCount' => $item['Count']['memberCount'],
                    'endDay' => $item['endDay'],
                    'endTime' => $item['endTime'],
                    'startDay' => $item['startDay'],
                    'startTime' => $item['startTime'],
                    'nowTime' => $nowTime,
                );

                if (isset($item['Join']['user_id'])) {
                    $res[$key]['role'] = $item['Join']['role'];
                    $res[$key]['requestStatus'] = $item['Join']['requestStatus'];
                    $res[$key]['isCreator'] = $item['Join']['user_id'] == $item['user_id'] ? 1 : 0;
                }
            }
        }
        return new JsonModel(array(
            'items' => $res,
        ));
    }

    public function groupjoinAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $id = $query['id'];

        $user = \Core\Auth::getLoginUser(); 

        if(!$id || !$user){
            return new JsonModel(array(
                'items' => array(),
            ));
        }

        $idArray = explode('-',$id);

        $query = array(
            'id' => $idArray,
            'noLimit' => true,
        );

        $itemModel = Api::_()->getModel('Group\Model\Group');
        $items = $itemModel->setItemList($query)->getGroupList();
        $items = $items->toArray(array(
            'self' => array(
                'id',
                'user_id',
                'memberEnable',
                'memberLimit',
            ),
            'join' => array(
                'Count' => array(
                    '*',
                ),
            ), 
        ));

        $joinList = array();
        if($user) {
            $joinModel = Api::_()->getModel('Group\Model\GroupUser');
            $joinList = $joinModel->setItemList(array(
                'group_id' => $idArray,
                'user_id' => $user['id']
            ))->getGroupUserList()->toArray();
        }
        $items = $itemModel->combineList($items, $joinList, 'Join', array('id' => 'group_id'));

        if (count($items) > 0) {
            $res = array();
            foreach ($items as $key=>$item) {
                $res[$key] = array(
                    'id' => $item['id'],
                    'user_id' => $item['user_id'],
                    'memberEnable' => $item['memberEnable'],
                    'memberLimit' => $item['memberLimit'],
                    'memberCount' => $item['Count']['memberCount'],
                );
                
                if (isset($item['Join']['user_id'])) {
                    $res[$key]['role'] = $item['Join']['role'];
                    $res[$key]['requestStatus'] = $item['Join']['requestStatus'];
                    $res[$key]['isCreator'] = $item['Join']['user_id'] == $item['user_id'] ? 1 : 0;
                }
            }
        }
        return new JsonModel(array(
            'items' => $res,
        ));
    }

    public function relationshipAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $id = $query['id'];

        $user = \Core\Auth::getLoginUser(); 

        if(!$id || !$user){
            return new JsonModel(array(
                'items' => array(),
            ));
        }

        $idArray = explode('-',$id);

        $query = array(
            'id' => $idArray,
            'noLimit' => true,
        );

        $itemModel = Api::_()->getModel('User\Model\User');
        $items = $itemModel->setItemList($query)->getUserList();
        $items = $items->toArray(array(
            'self' => array(
            ),
        ));

        $joinList = array();
        if($user) {
            $selectQuery = array(
                'user_id' => $idArray,
                'friend_id' => $user['id'],
            );
            $joinModel = Api::_()->getModel('User\Model\Friend');
            $joinList = $joinModel->setItemList($selectQuery)->getFriendList();
            $joinList = $joinList ? $joinList->toArray() : array();
        }
        $items = $itemModel->combineList($items, $joinList, 'Friend', array('id' => 'user_id'));

        return new JsonModel(array(
            'items' => $items,
        ));   
    }

    public function followAction()
    {
        $this->changeViewModel('json');
        $query = $this->getRequest()->getQuery();
        $id = $query['id'];

        $user = \Core\Auth::getLoginUser(); 

        if(!$id || !$user){
            return new JsonModel(array(
                'items' => array(),
            ));
        }

        $idArray = explode('-',$id);

        $query = array(
            'id' => $idArray,
            'noLimit' => true,
        );

        $itemModel = Api::_()->getModel('User\Model\User');
        $items = $itemModel->setItemList($query)->getUserList();
        $items = $items->toArray(array(
            'self' => array(
            ),
        ));

        $joinList = array();
        if($user) {
            $selectQuery = array(
                'user_id' => $idArray,
                'follower_id' => $user['id'],
            );
            $joinModel = Api::_()->getModel('Activity\Model\Follow');
            $joinList = $joinModel->setItemList($selectQuery)->getFollowList();
            $joinList = $joinList ? $joinList->toArray() : array();
        }
        $items = $itemModel->combineList($items, $joinList, 'Follow', array('id' => 'user_id'));

        return new JsonModel(array(
            'items' => $items,
        ));   
    }

    public function noticecountAction()
    {
        $mine = \Core\Auth::getLoginUser(); 

        $count = 0;

        if ($mine) {
            $query = array(
                'user_id' => $mine['id'],
                'readFlag' => 0,
                'noLimit' => true,
            );

            $itemModel = Api::_()->getModel('Notification\Model\Notice');
            $items = $itemModel->setItemList($query)->getNoticeList();
            $count = count($items->toArray());
        }

        $this->changeviewmodel('Json');
        return new JsonModel(array(
            'count' => $count,
        )); 
    }
}
