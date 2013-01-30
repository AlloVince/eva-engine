<?php
namespace Avnpc\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Zend\View\Model\FeedModel,
    Zend\Feed\Writer\Feed;

class FeedController extends ActionController
{
    public function indexAction()
    {
        $this->getEventManager()->attach('render', function($event){
            $app          = $event->getTarget();
            $locator      = $app->getServiceManager();
            $view         = $locator->get('Zend\View\View');
            $feedStrategy = $locator->get('ViewFeedStrategy');
            $view->getEventManager()->attach($feedStrategy, 100);
        }, 100);

        $query = $this->getRequest()->getQuery();
        $form = new \Blog\Form\PostSearchForm();
        $form->bind($query);
        if($form->isValid()){
            $query = $form->getData();
        } else {
            return array(
                'items' => array(),
            );
        }
        $query['status'] = 'published';

        $itemModel = Api::_()->getModel('Blog\Model\Post');
        $items = $itemModel->setItemList($query)->getPostList(array(
            'self' => array(
                '*',
            ),
            'join' => array(
                'Text' => array(
                    'self' => array(
                        '*',
                        'getContentHtml()',
                    ),
                ),
                'Categories' => array(
                ),
            ),
        ));
        $view = new FeedModel(array(
            'entries' => $items,
        ));
        return $view;
    }
}
