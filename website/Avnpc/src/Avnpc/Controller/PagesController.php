<?php
namespace Avnpc\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class PagesController extends ActionController
{
    protected $addResources = array(
    );

    public function getAction()
    {
        $id = $this->params('id');
        $postModel = Api::_()->getModel('Blog\Model\Post');
        $postinfo = $postModel->getPost($id);
        if($postinfo){
            header('HTTP/1.1 301 Moved Permanently');
            return $this->redirect()->toUrl('/pages/' . $postinfo['urlName']);
        }
    }

    public function indexAction()
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
        if(!$item || $item['status'] != 'published'){
            $item = array();
            $this->getResponse()->setStatusCode(404);
        }

        if($item){
            $item['Prev'] = $itemModel->getItem()->getDataClass()->where(array(
                "id < {$item['id']}"
            ))
            ->where(array("status" => "published"))
            ->order('id DESC')->find('one');

            $item['Next'] = $itemModel->getItem()->getDataClass()->where(array(
                "id > {$item['id']}"
            ))
            ->where(array("status" => "published"))
            ->order('id ASC')->find('one');
        }

        $comments = array();
        if($item){
            $commentsTable = Api::_()->getDbTable('Blog\DbTable\Comments');
            $comments = $commentsTable->where(array("post_id = {$item['id']}"))->find('all');
        }
        $view = new ViewModel(array(
            'item' => $item,
            'comments' => $comments,
        ));
        $view->setTemplate('avnpc/pages/get');
        $this->pagecapture();
        return $view;
    }
}
