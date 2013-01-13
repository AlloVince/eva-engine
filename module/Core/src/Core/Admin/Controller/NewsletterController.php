<?php
namespace Core\Admin\Controller;

use Core\Form,
    Eva\Api,
    Eva\Mvc\Controller\RestfulModuleController,
    Eva\View\Model\ViewModel,
    Core\Mail,
    Eva\Mail\Message,
    Core\Admin\MultiForm;

class NewsletterController extends RestfulModuleController
{
    protected $addResources = array(
        'send',
    );
    
    protected $renders = array(
        'restPostNewsletterSend' => 'newsletter/send',    
        'restGetNewsletterSend' => 'newsletter/send',    
    );

    public function restIndexNewsletter()
    {
        $query = $this->getRequest()->getQuery();

        $itemModel = Api::_()->getModel('Core\Model\Newsletter');
        $items = $itemModel->setItemList($query)->getNewsletterList();
        $paginator = $itemModel->getPaginator();

        return array(
            'items' => $items,
            'query' => $query,
            'paginator' => $paginator,
        );
    }

    public function restGetNewsletterSend()
    {
        $itemModel = Api::_()->getModel('Core\Model\Newsletter');
        $items = $itemModel->setItemList(array('noLimit' => true))->getNewsletterList();
        $items = $items->toArray();

        $bcc = '';

        if ($items) {
            foreach ($items as $item) {
                $bccArray[] = $item['email'];
            }
        
            $bcc = implode(',', $bccArray);
        }

        return array(
            'item' => array('bcc' => $bcc),
        ); 
    }

    public function restPostNewsletterSend()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();

        $form = new \Core\Form\NewsletterForm();
        $form->bind($postData);
        if ($form->isValid()) {
            $item = $form->getData();

            $file = array();
            if($form->getFileTransfer()->isUploaded()) {
                $form->getFileTransfer()->receive();
                $files = $form->getFileTransfer()->getFileInfo();
                $file = $files['attachment'];
            }

            $mail = new \Core\Mail();
            $message = $mail->getMessage();
            
            $emails = explode(',', $item['bcc']);
            foreach ($emails as $email) {
                $message->addBcc($email);
            }

            $message->setSubject($item['subject'])
                ->setBody($item['content']);

            if($file){
                $message->addAttachment($file['tmp_name']);
            }
            $mail->send();
            
            return $this->redirect()->toUrl('/admin/core/newsletter');

        } else {
        }
    }

    public function restPostNewsletter()
    {
        $request = $this->getRequest();
        $postData = $request->getPost();
        $dataArray = MultiForm::getPostDataArray($postData);

        $postTable = Api::_()->getDbTable('Core\DbTable\Newsletters');

        foreach($dataArray as $key => $array){
            $postTable->where(array(
                'user_id' => $array['id'],
            ))->remove();
        }

        $this->redirect()->toUrl('/admin/core/newsletter');
    }

    public function restPutNewsletter()
    {
        $postData = $this->params()->fromPost();
        $form = new Form\NewsletterEditForm();
        $form->useSubFormNewsletter()
            ->bind($postData);

        $flashMesseger = array();

        if ($form->isValid()) {
            $postData = $form->getData();
            $itemModel = Api::_()->getModel('Core\Model\Newsletter');
            $newsletterId = $itemModel->setItem($postData)->saveNewsletter();

            $this->flashMessenger()->addMessage('newsletter-edit-succeed');
            $this->redirect()->toUrl('/admin/newsletter/' . $postData['id']);

        } else {
        }

        return array(
            'form' => $form,
            'item' => $postData,
        );
    }
}
