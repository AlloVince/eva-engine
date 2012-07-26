<?php
namespace File\Admin\Controller;

use File\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Core\Admin\MultiForm,
    Core\Controller\Exception;

class MultiController extends RestfulModuleController
{
    protected $addResources = array(
        'status',
        'reorder',
    );

    protected $renders = array(
        'restPostMultiReorder' => 'blank',
        'restPostMultiStatus' => 'blank',
    );

    public function restPostMultiReorder()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $dataArray = MultiForm::getPostDataArray($postData);

        $postTable = Api::_()->getDbTable('File\DbTable\Posts');

        foreach($dataArray as $key => $array){
            $postTable->where(array('id' => $array['id']))->save(array(
                'orderNumber' => $array['order']
            ));
        }
        $this->redirect()->toUrl('/admin/blog/');
    }

    public function restPostMultiStatus()
    {
        $postStatus = $this->params('id');
        if(!$postStatus) {
            throw new Exception\BadRequestException(); 
        }
        
        $request = $this->getRequest();
        $postData = $request->getPost();
        $dataArray = MultiForm::getPostDataArray($postData);
        
        $postTable = Api::_()->getDbTable('File\DbTable\Posts');
        $postTable->where(function($where) use ($dataArray){
            foreach($dataArray as $key => $array){
                $where->equalTo('id', $array['id']);
                $where->or;
            }
            return $where;
        })->save(array(
            'status' => $postStatus
        ));
        
        $this->redirect()->toUrl('/admin/blog/');
    }
}
