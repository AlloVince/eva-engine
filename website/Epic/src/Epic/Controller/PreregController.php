<?php
namespace Epic\Controller;

use Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Eva\Api,
    Epic\Form;

class PreregController extends ActionController
{
    protected $addResources = array(
    );

    public function indexAction()
    {
        $res = array();
        $this->layout('layout/coming');

        $view = new ViewModel(array(
            'reg' => $this->params()->fromQuery('reg')
        ));
        return $view;
    }

    public function getAction()
    {
        if($_POST){
            $this->layout('layout/empty');
            $reg = $this->regAction();
            $view = new ViewModel($reg);
            $view->setTemplate('epic/reg/thankyou');
            return $view;
        }
        $res = array();
        $this->layout('layout/empty');
        $id = $this->params('id');
        $view = new ViewModel(array(
            'id' => $id
        ));
        $view->setTemplate('epic/reg/' . $id);
        return $view;
    }

    public function regAction()
    {
        $id = $this->params('id');
        $request = $this->getRequest();
        $postData = $request->getPost();

        $idMap = array(
            'corporate' => 11,
            'connoisseur' => 12,
            'professional' => 13,
        );
        $roleId = $idMap[$id];
        $formName = 'Epic\\Form\\' . ucfirst($id) . 'Form';
        $form = new $formName();
        $form->addSubForm('UserRoleFields', new \User\Form\UserRoleFieldsForm(null, $roleId))
        ->useSubFormGroup()
        ->bind($postData);

        if ($form->isValid()) {
            $postData = $form->getData();
            $postData['status'] = 'deleted';
            $itemModel = Api::_()->getModel('Epic\Model\User');
            $itemId = $itemModel->setItem($postData)->preRegister();
            //$this->flashMessenger()->addMessage('item-create-succeed');
            //$this->redirect()->toUrl('/admin/user/' . $itemId);

        } else {
            //p($form->getMessages());
        }

        return array();
    }
}
