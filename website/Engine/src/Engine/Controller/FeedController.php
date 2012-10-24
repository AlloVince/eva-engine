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
        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $items = $itemModel->getItem()->getDataClass()->where(array(
            'id' => array(2, 1)
        ))->find('all');

        $items = $itemModel->getUserActivityList($user['id'])->getActivityList(array(
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
                )
            ),
        ));

        //p($items, 1);
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
                )
            ),
        ));

        //$this->pagecapture();
        return array(
            'item' => $item,
        );
    }
}
