<?php
namespace Notification\Admin\Controller;

use Notification\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class RemoveController extends RestfulModuleController
{
    protected $renders = array(
    );

    public function restGetRemove()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Notification\Model\Notification');
        $item = $itemModel->getNotification($id)->toArray();
        return array(
            'callback' => $this->params()->fromQuery('callback'),
            'item' => $item,
        );
    }
}
