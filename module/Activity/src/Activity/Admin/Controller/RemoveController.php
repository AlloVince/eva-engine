<?php
namespace Activity\Admin\Controller;

use Activity\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class RemoveController extends RestfulModuleController
{
    protected $renders = array(
        'restGetRemove' => 'activity/delete',    
    );

    public function restGetRemove()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Activity\Model\Activity');
        $item = $itemModel->getActivity($id)->toArray();
        return array(
            'callback' => $this->params()->fromQuery('callback'),
            'item' => $item,
        );
    }
}
