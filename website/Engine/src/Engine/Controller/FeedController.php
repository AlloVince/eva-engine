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
            'order' => 'iddesc'
        );

        $user = \Core\Auth::getLoginUser();
        if(!$user){
            return $this->getResponse()->setStatusCode(401);
        }

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

        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $activityList = $itemModel->getUserActivityList($user['id'])->getActivityList($feedMap);

        $userList = array();
        $userList = $itemModel->getUserList()->toArray();

        $forwardActivityList = $itemModel->getForwardActivityList()->getActivityList($feedMap);
        
        $activityList = $itemModel->combineList($activityList, $userList, 'User', array('user_id' => 'id'));
        $items = $itemModel->combineList($activityList, $forwardActivityList, 'ForwardActivity', array('reference_id' => 'id'));

        return array(
            'items' => $items,
            'query' => $query,
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
