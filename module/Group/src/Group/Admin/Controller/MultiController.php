<?php
namespace Group\Admin\Controller;

use Group\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Core\Admin\MultiForm,
    Core\Controller\Exception;

class MultiController extends RestfulModuleController
{
    protected $addResources = array(
        'status',
    );

    protected $renders = array(
        'restPostMultiStatus' => 'blank',
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
        
        $postTable = Api::_()->getDbTable('Group\DbTable\Groups');
        $postTable->where(function($where) use ($dataArray){
            foreach($dataArray as $key => $array){
                $where->equalTo('id', $array['id']);
                $where->or;
            }
            return $where;
        })->save(array(
            'status' => $postStatus
        ));
        
        $this->redirect()->toUrl('/admin/group/');
    }
}
