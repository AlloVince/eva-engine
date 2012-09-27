<?php
namespace Engine\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel;

class PagesController extends RestfulModuleController
{

    public function indexAction()
    {
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
        $items = $itemModel->setItemList($query)->getPostList();
        $paginator = $itemModel->getPaginator();

        //$this->pagecapture();
        return array(
            'items' => $items,
            'paginator' => $paginator,
        );
    }

    public function getAction()
    {
        $id = $this->params('id');
        $itemModel = Api::_()->getModel('Blog\Model\Post');
        $item = $itemModel->getPost($id, array(
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
            'proxy' => array(
                'File\Item\File::PostCover' => array(
                    'self' => array(
                        '*',
                        'getThumb()',
                    )
                )
            ),
        ));
        if($item['status'] != 'published'){
            $item = array();
            $this->getResponse()->setStatusCode(404);
        }
        //$this->pagecapture();
        return array(
            'item' => $item,
        );
    }

}
