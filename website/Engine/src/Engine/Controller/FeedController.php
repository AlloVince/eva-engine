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

        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $items = $itemModel->setItemList($query)->getActivityList(array(
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
}
