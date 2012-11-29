<?php
namespace Engine\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Core\Auth,
    Eva\View\Model\ViewModel;

class FeedController extends RestfulModuleController
{

    public function indexAction()
    {
        $query = array(
            'page' => $this->params()->fromQuery('page', 1),
        );

        $user = Auth::getLoginUser();
        $userId = $user['id'];
        $page = $this->params()->fromQuery('page', 1);

        $feedMap = array(
            'self' => array(
                '*',
                'getContentHtml()',
                'getVideo()',
            ),
            'join' => array(
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
            ),
        );
        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $activityList = $itemModel->getUserActivityList(array(
            'user_id' => $userId,
            'page' => $page,
        ))->getActivityList($feedMap);
        $paginator = $itemModel->getUserActivityPaginator();

        $userList = array();
        $userList = $itemModel->getUserList()->toArray();

        $forwardActivityList = $itemModel->getForwardActivityList()->getActivityList($feedMap);
        
        $activityList = $itemModel->combineList($activityList, $userList, 'User', array('user_id' => 'id'));
        $items = $itemModel->combineList($activityList, $forwardActivityList, 'ForwardActivity', array('reference_id' => 'id'));

        return array(
            'user' => $user,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function getAction()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Activity\Model\Activity');


        $item = $itemModel->getActivity($id, array(
            'self' => array(
                '*',
                'getContentHtml()',
            ),
            'join' => array(
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
                'User' => array(
                    'self' => '*'
                ),
                'ForwardActivity' => array(
                    'self' => array(
                        '*',
                        'getContentHtml()',
                    ),
                    'join' => array(
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

        $feedMap = array(
            'self' => array(
                '*',
                'getContentHtml()',
            ),
            'join' => array(
                'File' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                ),
            ),
        );
        $commentActivityList = $itemModel->getCommentActivityList()->getActivityList($feedMap);

        $userModel = Api::_()->getModel('User\Model\User');
        $userItem = $userModel->getUser($item['user_id'])->toArray();
        $item['User'] = $userItem;

        $userList = $itemModel->getUserList()->toArray();
        $items = $itemModel->combineList($commentActivityList, $userList, 'User', array('user_id' => 'id'));

        return array(
            'item' => $item,
            'items' => $items,
        );
    }
}
