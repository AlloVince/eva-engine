<?php
namespace Blog\Controller;

use Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Blog\Model\PostTable,
    Eva\View\Model\ViewModel;

class PostController extends RestfulModuleController
{
    protected $addResources = array(
        'page',    
    );

    public function restGetPost()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');

        $postModel = Api::_()->getModel('Blog\Model\Post');
        $postTable = $postModel->getItemTable();
        $postinfo = $postTable->find($id);
        $postinfo = $postModel->getItemArray($postinfo, array(
            'Url' => array('urlName', 'getUrl', 'callback'),
            'Text' => array(
                'contentHtml' => array('contentHtml', 'getContentHtml'),
                //'MetaKeywordsArray' => array('metaKeyword', 'getMetaKeywordArray') 
            ),
        ));

        return array(
            //'form' => $form,
            'post' => $postinfo,
        );
    }

}
