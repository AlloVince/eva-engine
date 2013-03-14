<?php
namespace Epic\Controller;

use Eva\Mvc\Controller\ActionController;
use Eva\View\Model\ViewModel;
use Eva\Api;
use Core\Auth;
use User\Form;

class ShareController extends ActionController
{
    public function indexAction()
    {
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
        $activityList = $itemModel->setItemList(array(
            'hasFile' => 1,
            'page' => $this->params()->fromQuery('page', 1),
            'order' => 'iddesc',
        ))->getActivityList($feedMap);
        $paginator = $itemModel->getPaginator();

        return array(
            'items' => $activityList,
            'paginator' => $paginator,
        );

    }

}
