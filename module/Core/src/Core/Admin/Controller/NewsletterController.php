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
            'bcc' => $bcc,
        ); 
    }

    public function restPostNewsletterSend()
    {
        $params = $this->params()->fromPost();
        
        $subject = $params['title'];
        $content = $params['content'];
        $bcc     = $params['bcc'];
        
        if (!$subject || !$content || !$bcc) {
            exit;
        }
        
        $config = $this->getServiceLocator()->get('config');
        $config['mail'];
        
        $mail = new Mail();
        $message = $mail->getMessage();
        //$message->addFrom($config['mail']['from']['email'], $config['mail']['from']['name']);
        
        $emails = explode(',', $bcc);

        foreach ($emails as $email) {
            $message->addBcc($email);
        }
        
        $message->setSubject($subject);
        $message->setBody($content);
        $mail->send($message);
        
        $this->redirect()->toUrl('/admin/core/newsletter');
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
