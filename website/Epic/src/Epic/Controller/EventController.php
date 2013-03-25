<?php
namespace Epic\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;
use Core\Auth;
use Epic\Form;
use Eva\Date\Calendar;

class EventController extends ActionController
{
    protected $eventItem;
    
    protected $members;
    
    public function indexAction()
    {
        return $this->listAction();
    }

    public function listAction()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();

        $form = new \Epic\Form\EventSearchForm();
        $form->bind($query)->isValid();
        $selectQuery = $form->getData();

        if(!$selectQuery){
            $selectQuery = array(
                'page' => 1
            );
        }
        $selectQuery['eventStatus'] = 'active';
        $selectQuery['visibility']  = 'public';
        if($selectQuery['city'] == 'mycity'){
            $selectQuery['city'] = $this->cookie()->crypt(false)->read('city');
        }
        
        $selectMap = array(
            'self' => array(
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
                    '*'
                ),
            ),
        );
        
        $groupId = $this->params('group_id');
        $inGroup = $this->params('inGroup');
        $order = $this->params('order');
        
        if ($groupId || $inGroup) { 
            $itemModel = Api::_()->getModel('Group\Model\Event'); 
            $selectQuery['inGroup'] = true;
            $selectQuery['group_id'] = $groupId;
            $selectQuery['order'] = $order;
            $selectMap['join']['Group'] = array('*');
        } else {
            $itemModel = Api::_()->getModel('Event\Model\Event');
        }    
        
        $items = $itemModel->setItemList($selectQuery)->getEventdataList();
        $items = $items->toArray($selectMap);
        $paginator = $itemModel->getPaginator();

        $user = Auth::getLoginUser();
        $joinList = array();
        if($user) {
            $joinModel = Api::_()->getModel('Event\Model\EventUser');
            $joinList = $joinModel->setItemList(array(
                'user_id' => $user['id']
            ))->getEventUserList()->toArray();
        }
        $items = $itemModel->combineList($items, $joinList, 'Join', array('id' => 'event_id'));

        //Public User Area
        $this->forward()->dispatch('UserController', array(
            'action' => 'user',
            'id' => $user['id'],
        ));

        $startDay = $this->params()->fromQuery('start');
        $calendarModel = Api::_()->getModel('Event\Model\Calendar');
        $calendarArray = $calendarModel->getEventCalendar(array(
            'startDay' => $startDay,
        ));
        $eventList = $calendarModel->getEventList();
        $eventList = $eventList ? $eventList->toArray() : array();
        $today = $calendarArray['today']['datedb'];
        $week = array();
        foreach($calendarArray['days'] as $weekArray){
            if($week){
                break;
            }
            foreach($weekArray as $day){
                if($day['datedb'] == $today){
                    $week = $weekArray;
                    break;
                }
            }
        }

        return array(
            'calendar' => $calendarArray,
            'week' => $week,
            'form' => $form,
            'items' => $items,
            'eventList' => $eventList,
            'query' => $query,
            'paginator' => $paginator,
        );      
    }

    public function getAction()
    {
        list($item, $members) = $this->eventAction();
        
        list($items, $paginator) = $this->forward()->dispatch('FeedController', array(
            'action' => 'index',
            'event_id' => $item['id'],
        ));
        
        $albumModel = Api::_()->getModel('Event\Model\Album');
        $album = $albumModel->getEventAlbum($item['id']);

        if ($album->id) {
            $imageModel = Api::_()->getModel('Album\Model\AlbumFile');

            $query = array(
                'album_id' => $album['id'],
                'noLimit' => true,
            );

            $images = $imageModel->setItemList($query)->getAlbumFileList();
            $images->toArray(array(
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
        }

        $view = new ViewModel(array(
            'item' => $item,
            'items' => $items,
            'images' => isset($images) ? $images : null,
            'members' => $members,
            'paginator' => $paginator,
        ));
        return $view; 
    }

    public function eventAction()
    {
        if($this->eventItem && $this->members){
            return array($this->eventItem, $this->members);
        }   

        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        if(!$id){
            return array();
        }

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
                'Category' => array(
                    '*'
                ),
                'Count' => array(
                    '*'
                ),
                'Tags' => array(
                    '*'
                ),
            ),
        ));

        if(!$item || $item['eventStatus'] != 'active'){
            $item = array();
            $this->getResponse()->setStatusCode(404);
        }

        $user = Auth::getLoginUser(); 
        //Public User Area
        $this->forward()->dispatch('UserController', array(
            'action' => 'user',
            'id' => $user['id'],
        ));

        if($user) {
            $joinModel = Api::_()->getModel('Event\Model\EventUser');
            $joinList = $joinModel->setItemList(array(
                'user_id' => $user['id'],
                'event_id' => $item['id'],
            ))->getEventUserList()->toArray();

            if (count($joinList) > 0) {
                $item['Join'] = $joinList[0];
            }
        } 

        $memberModel = Api::_()->getModel('Event\Model\EventUser'); 
        $members = $memberModel->setItemList(array('event_id' => $item['id'], 'noLimit' => true))->getEventUserList();
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

        $this->eventItem = $item;
        $this->members = $members;

        return array($item, $members); 
    }

    public function removeAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData = $this->params()->fromPost();
            $callback = $this->params()->fromPost('callback');

            $form = new \Event\Form\EventDeleteForm();
            $form->bind($postData);
            if ($form->isValid()) {

                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Event\Model\Event');
                $itemModel->setItem($postData)->removeEventdata();
                $callback = $callback ? $callback : '/my/event/';
                $this->redirect()->toUrl($callback);

            } else {
                return array(
                    'post' => $postData,
                );
            }

        } else {
            $id = $this->params('id');
            $itemModel = Api::_()->getModel('Event\Model\Event');
            $item = $itemModel->getEventdata($id)->toArray();

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
            return array(
                'params' => $request->getQuery()
            );
        }

        $postData = $request->getPost();
        $callback = $this->params()->fromPost('callback');
        $form = new Form\EventCreateForm();
        $form->useSubFormGroup()
            ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Event\Model\Event');
            $eventId = $itemModel->setItem($postData)->createEventdata();
            $callback = $callback ? $callback : '/my/event/';
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
        $viewModel->setTemplate('epic/event/create');
        if ($request->isPost()) {
            $postData = $request->getPost();
            $callback = $this->params()->fromPost('callback');
            $form = new Form\EventEditForm();
            $form->useSubFormGroup()
                ->bind($postData);

            if ($form->isValid()) {
                $postData = $form->getData();
                $itemModel = Api::_()->getModel('Event\Model\Event');
                $eventId = $itemModel->setItem($postData)->saveEventdata();
                $callback = $callback ? $callback : '/my/event/';
                $this->redirect()->toUrl($callback);

            } else {
            }

            $viewModel->setVariables(array(
                'form' => $form,
                'item' => $postData,
            ));
        } else {
            $id = $this->params('id');
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
                    'Category' => array(
                        '*'
                    ),
                    'Tags' => array(
                        '*'
                    ),
                ),
            ));
            if(isset($item['EventFile'][0])){
                $item['EventFile'] = $item['EventFile'][0];
            }

            $viewModel->setVariables(array(
                'item' => $item,
            ));
        }

        return $viewModel;
    }

    public function albumUploadAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        
        $albumModel = Api::_()->getModel('Event\Model\Album');

        list($item, $members) = $this->eventAction();

        $album = $albumModel->getEventAlbum($item['id'],array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
                'Category' => array(
                    '*'
                ),
            ),
        ));
        
        if (!isset($album['id'])) {
            $makeAlbumUrl = '/event/albums/' . '?' . http_build_query(array(
                'callback' => '/event/' . $item['urlName'] . '/album/upload/',
                'title' => $item['title'],
                'urlName' => '',
                'description' => '',
                'event_id' => $item['id'],
            ));
            return $this->redirect()->toUrl($makeAlbumUrl);
        }

        return array(
            'item' => $item,
            'album' => $album,
            'members' => $members,
        );   
    }

    public function albumGetAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();

        $albumModel = Api::_()->getModel('Event\Model\Album');

        list($item, $members) = $this->eventAction();

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

        return array(
            'item' => $item,
            'items' => $items,
            'album' => $album,
            'members' => $members,
        );   
    }
}
