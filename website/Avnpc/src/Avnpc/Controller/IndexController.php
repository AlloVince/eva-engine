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
        $tag = $this->params('tag');
        $page = 1;
        if(is_numeric($tag)){
            $query = array(
                'page' => $tag
            );
            $tag = '';
        } else {
            $query = array(
                'tag' => $tag,
                'page' => $page = $this->params('page', 1)
            );
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
        if($this->params('page') || $tag){
            $this->pagecapture();
        } else {
            $this->pagecapture('index');
        }
        return $view;
    }
}
