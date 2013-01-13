<?php
namespace Avnpc\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class IndexController extends ActionController
{
    protected $addResources = array(
    );

    public function indexAction()
    {
        $page = $this->params('page', 1);
        $tag = '';
        if(is_numeric($page)){
            $query = array(
                'tag' => $page
            );
        } else {
            $query = array(
                'page' => $page
            );
            $tag = $page;
        }

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
        $items = $itemModel->setItemList($query)->getPostList();
        $paginator = $itemModel->getPaginator();

        $view = new ViewModel(array(
            'items' => $items,
            'paginator' => $paginator,
            'query' => $query,
        ));
        $view->setTemplate('avnpc/index');
        if($this->params('page')){
            $this->pagecapture();
        } else {
            $this->pagecapture('index');
        }
        return $view;
    }
}
