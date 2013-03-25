<?php
namespace Epic\Controller;

use Zend\View\Model\ViewModel;
use Eva\Mvc\Controller\ActionController;
use Activity\Form;
use Zend\Mvc\MvcEvent;
use Eva\Api;
use Epic\Exception;
use Core\Auth;

class HomeController extends ActionController
{

    public function dashboardAction()
    {
        $user = Auth::getLoginUser(); 
        //Public User Area
        $this->forward()->dispatch('UserController', array(
            'action' => 'user',
            'id' => $user['id'],
        ));

        $tagModel = Api::_()->getModel('Group\Model\Tag');
        $tags = $tagModel->setItemList(array(
            'rows' => 30,
            'order' => 'groupcountdesc',
        ))->getTagList();
        $tags = $tags ? $tags->toArray() : array();

        return array(
            'tags' => $tags
        );
    }


    public function indexAction()
    {
        $query = array(
            'page' => $this->params()->fromQuery('page', 1),
        );

        $user = \Core\Auth::getLoginUser();
        if(!$user){
            throw new Exception\UnauthorizedException('Unauthorized');
        }

        //Public User Area
        $this->forward()->dispatch('UserController', array(
            'action' => 'user',
            'id' => $user['id'],
        ));


        list($items, $paginator) = $this->forward()->dispatch('FeedController', array(
            'action' => 'index',
            'user_id' => $user['id'],
        ));

        $this->getServiceLocator()->get('Application')->getEventManager()->attach(MvcEvent::EVENT_RENDER, function($event) {
            $viewModel = $event->getViewModel();
            $viewModel->setVariables(array(
                'viewAsGuest' => 0
            ));
            $viewModelChildren = $viewModel->getChildren();
            foreach($viewModelChildren as $childViewModel){
                $childViewModel->setVariables(array(
                    'viewAsGuest' => 0
                ));
            }
        }, -100);

        return array(
            'user' => $user,
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }
}
