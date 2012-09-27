<?php
namespace Scaffold\Controller;

use Eva\Mvc\Controller\RestfulModuleController,
    Eva\Api,
    Eva\Db\Metadata\Metadata,
    Core\Admin\MultiForm,
    Eva\View\Model\ViewModel;

class TestdataController extends RestfulModuleController
{
    protected $addResources = array(
        'posts'
    );
    
    protected $renders = array(
        'restPutTestdataPosts' => 'testdata/posts',    
    ); 
    
    public function restGetTestdataPosts()
    {
        $query = $this->getRequest()->getQuery();

        $form = new \Blog\Form\CategoryForm();
       
        $categoryModel = Api::_()->getModel('Blog\Model\Category');
        $categories = $categoryModel->setItemList($query)->getCategoryList();
        $paginator = $categoryModel->getPaginator();

        return array(
            'form' => $form,
            'categories' => $categories,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restPostTestdataPosts()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $dataArray = MultiForm::getPostDataArray($postData);
        
        $itemModel = Api::_()->getModel('Blog\Model\Category');
        $postModel = Api::_()->getModel('Blog\Model\Post');
        
        $content = "国际在线专稿：据英国广播公司8月10日报道，71岁的巴西球王贝利近日在英国伦敦观看奥运会比赛。贝利接受媒体采访时表示，他认为伦敦奥运会非常成功，而作为下届奥运会举办国，巴西还未做好准备，恐难超越伦敦奥运会。
巴西将在2014年举办世界杯，2年后又将迎来41届夏季奥运会。贝利说，“当前事情看起来不大妙，我们的建筑项目还有些小问题。”他表示，交通和传媒方面可能存在最大的问题，希望届时能顺利解决。距离举办巴西世界杯只有两年时间了，贝利似乎对巴西的举办能力缺乏信心。他说，“我已经和总统罗塞夫就此事讨论过，她说会尽最大努力筹备组织奥运会，但我们还没有真正准备好。”
这位71岁的足球先生、足球运动的世界代言人表示，希望首次举办奥运会将为巴西带来深厚的影响，但巴西也应该交给世界一份满意的答卷。
当被问及牙买加田径运动员“闪电”博尔特是否像他和拳王阿里一样，具备成为最伟大的运动员的资格，他说，“当然，这毫无疑问。”----------------";

        foreach($dataArray as $key => $array){
            if ($array['order'] <= 0 || !$array['id']) {
                continue;
            }

            $categoryinfo = $itemModel->setItemParams($array['id'])->getCategory();
            
            if (!$categoryinfo) {
                continue;
            }
            
            for($i=1;$i<=$array['order'];$i++) {

                $postData = array(
                    'title' => $categoryinfo['categoryName'] . "-测试数据-" . $i,
                    'Text' => array(
                        'content' => $content,
                    ),
                    'status' => 'published',
                    'codeType' => 'markdown',
                    'visibility' => 'public',
                    'commentStatus' => 'open',
                    'commentType' => 'local',
                    'CategoryPost' => array(
                        'category_id' => $array['id'],
                        'post_id' => 0,
                    ),
                );

                $form = new \Blog\Form\PostForm();
                $subForms = array(
                    'Text' => array('Blog\Form\TextForm'),
                    'CategoryPost' => array('Blog\Form\CategoryPostForm'),
                    'FileConnect' => array('File\Form\FileConnectForm'),
                );
                $form->setSubforms($subForms)->init();
                $form->setData($postData)->enableFilters();
                if ($form->isValid()) {

                    $postData = $form->getData();
                    $postData = $form->fieldsMap($postData, true);
                    $postId = $postModel->setSubItemMap($subForms)->setItem($postData)->createPost();
                } else {

                } 
            }
        }

        $this->redirect()->toUrl('/scaffold/testdata/posts/');
    }
}
