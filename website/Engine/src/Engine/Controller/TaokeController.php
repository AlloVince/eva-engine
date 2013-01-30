<?php
namespace Engine\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Core\Auth,
    Eva\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Json;

class TaokeController extends RestfulModuleController
{
    public function indexAction()
    {
        $pid = $this->params('id');
        $nick = $this->params()->fromQuery('nick');
        $price = $this->params()->fromQuery('price', 0);

        $commission = new \Commission\Service\Taoke();
        $item = $commission->getProduct($pid, $nick);
        return array(
            'item' => $item,
            'price' => $price,
        );
    }
}
