<?php
namespace Event\Admin\Controller;

use Event\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Core\Admin\MultiForm,
    Core\Controller\Exception;

class MultiController extends RestfulModuleController
{
    protected $addResources = array(
        'status',
        'recommend',
    );

    protected $renders = array(
        'restPostMultiStatus' => 'blank',
        'restPostMultiRecommend' => 'blank',
    );

    public function restPostMultiStatus()
    {
        $postStatus = $this->params('id');
        if(!$postStatus) {
            throw new Exception\BadRequestException(); 
        }
        
        $request = $this->getRequest();
        $postData = $request->getPost();
        $dataArray = MultiForm::getPostDataArray($postData);
        
        $postTable = Api::_()->getDbTable('Event\DbTable\Events');
        $postTable->where(function($where) use ($dataArray){
            foreach($dataArray as $key => $array){
                $where->equalTo('id', $array['id']);
                $where->or;
            }
            return $where;
        })->save(array(
            'eventStatus' => $postStatus
        ));
        
        $this->redirect()->toUrl('/admin/event/');
    }

    public function restPostMultiRecommend()
    {
        $postStatus = $this->params('id');
        if(!$postStatus) {
            throw new Exception\BadRequestException(); 
        }
        
        $request = $this->getRequest();
        $postData = $request->getPost();
        $dataArray = MultiForm::getPostDataArray($postData);

        $postStatus = $postStatus == 'recommend' ? 1 : 0;

        $postTable = Api::_()->getDbTable('Event\DbTable\Events');
        $postTable->where(function($where) use ($dataArray){
            foreach($dataArray as $key => $array){
                $where->equalTo('id', $array['id']);
                $where->or;
            }
            return $where;
        })->save(array(
            'recommend' => $postStatus
        ));
        
        $this->redirect()->toUrl('/admin/event/');
    }
}
