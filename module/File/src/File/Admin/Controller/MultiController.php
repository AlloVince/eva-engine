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
        $fileData = $request->getPost();
        $dataArray = MultiForm::getPostDataArray($fileData);

        $fileTable = Api::_()->getDbTable('File\DbTable\Files');

        foreach($dataArray as $key => $array){
            $fileTable->where(array('id' => $array['id']))->save(array(
                'orderNumber' => $array['order']
            ));
        }
        $this->redirect()->toUrl('/admin/file/');
    }

    public function restPostMultiStatus()
    {
        $fileStatus = $this->params('id');
        if(!$fileStatus) {
            throw new Exception\BadRequestException(); 
        }
        
        $request = $this->getRequest();
        $fileData = $request->getPost();
        $dataArray = MultiForm::getPostDataArray($fileData);
        
        $fileTable = Api::_()->getDbTable('File\DbTable\Files');
        $fileTable->where(function($where) use ($dataArray){
            foreach($dataArray as $key => $array){
                $where->equalTo('id', $array['id']);
                $where->or;
            }
            return $where;
        })->save(array(
            'status' => $fileStatus
        ));
        
        $this->redirect()->toUrl('/admin/file/');
    }
}
