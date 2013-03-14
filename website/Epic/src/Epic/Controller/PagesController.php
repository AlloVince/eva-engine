<?php
namespace Epic\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;
use Epic\Exception;

class PagesController extends ActionController
{
    protected $addResources = array(
    );

    public function getAction()
    {
        $id = $this->params('id');
        $this->layout('layout/empty');
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
        if(!$item || $item['status'] != 'published'){
            $item = array();
            throw new Exception\PageNotFoundException('Post not found');
        }
        $view = new ViewModel(array(
            'post' => $item,
        ));
        return $view;
    }
}
