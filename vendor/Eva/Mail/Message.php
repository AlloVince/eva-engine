<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Mail
 */

namespace Eva\Mail;

use Zend\Mail\Exception;
use Zend\Mail\Headers;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part;
use Zend\Mime\Mime;

/**
 * @category   Zend
 * @package    Zend_Mail
 */
class Message extends \Zend\Mail\Message
{

    const VIEW_PATH_NAME  = 'defaultPath';
    const TEXT_MAIL = 'text/plain';
    const HTML_MAIL = 'text/html';

    /**
    * Template of the message
    *
    * @var string|object
    */
    protected $template;
    protected $templatePath;

    /**
    * @var Mvc View
    */
    protected $view;

    protected $viewModel;


    /**
    * Mail View Data
    *
    * @var string
    */
    protected $data;

    protected $attachments = array();

    protected $mailType;

    public function setMailType($mailType)
    {
        $this->mailType = $mailType;
        return $this;
    }

    public function getMailType()
    {
        if(!$this->mailType){
            return $this->mailType = self::TEXT_MAIL;
        }
        return $this->mailType;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    public function getTemplateText()
    {
        if(!$this->template){
            return '';
        }
        $view = $this->view;
        if(!$view instanceof PhpRenderer){
            throw new Exception\InvalidArgumentException(sprintf(
                '%s Mail template expects Zend\View\Renderer\PhpRenderer as view; received "%s"',
                __METHOD__,
                gettype($view)
            )); 
        }

        $viewModel = $this->viewModel;
        if(!$viewModel instanceof ViewModel){
            throw new Exception\InvalidArgumentException(sprintf(
                '%s Mail template expects Zend\View\Model\ViewModel as view model; received "%s"',
                __METHOD__,
                gettype($viewModel)
            )); 
        }

        $templatePath = $this->templatePath;
        if($templatePath){
            $resolverQueue = $view->resolver()->getIterator();
            $templatePathStack = $resolverQueue->top();
            $templatePathStack->setPaths(array(
                $templatePath
            ));
            $view->resolver()->attach($templatePathStack);

        }

        $viewModel->setTemplate($this->template);
        $viewModel->setVariables($this->data);

        $template = $view->render($viewModel);
        return $template;
    }

    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setViewModel(ViewModel $viewModel)
    {
        $this->viewModel = $viewModel;
        return $this;
    }

    public function getViewModel()
    {
        return $this->viewModel;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    public function addAttachment($attachmentOrFilePath, $options = array())
    {
        $defaultOptions = array(
            'encoding' => Mime::ENCODING_BASE64,
            'disposition' => Mime::DISPOSITION_ATTACHMENT,
        );
        $options = array_merge($defaultOptions, $options);
        if($attachmentOrFilePath instanceof MimeMessage){
            foreach($options as $key => $value){
                $attachmentOrFilePath->$key = $value;
            }
            return $this->attachments[] = $attachmentOrFilePath;
        }

        $attachmentHandler = fopen($attachmentOrFilePath, 'r');
        if(!$attachmentHandler){
            throw new Exception\InvalidArgumentException(sprintf('Failed to read attachment %s', $attachmentOrFilePath));
        }

        $attachment = new Part($attachmentHandler);
        $attachment->filename = $this->getAttachmentFileName($attachmentOrFilePath);
        $attachment->type = $this->getAttachmentMimeType($attachmentOrFilePath);
        foreach($options as $key => $value){
            $attachment->$key = $value;
        }
        return $this->attachments[] = $attachment;
    }

    protected function getAttachmentFileName($filePath)
    {
        $fileArray = explode(DIRECTORY_SEPARATOR, $filePath);
        $fileName = $fileArray[count($fileArray) - 1];
        if($fileName){
            return $fileName;
        }
        return 'attachment';
    }

    protected function getAttachmentMimeType()
    {
        return 'image/jpg';
    }

    public function getBody()
    {
        if(!$this->template && !$this->attachments){
            return $this->body;
        }

        if($this->template) {
            $template = $this->getTemplateText();
        } else {
            $template = $this->body;
        }

        $attachments = $this->attachments;
        if(!$attachments){
            return $this->body = $template;
        }


        $messageText = new Part($template);
        //Auto check email type is html
        if(false === strpos($template, '<')) {
            $messageText->type = self::TEXT_MAIL;
            $this->setMailType(self::TEXT_MAIL);
        } else {
            $messageText->type = self::HTML_MAIL;
            $this->setMailType(self::HTML_MAIL);
        }
        $messageText->encoding = Mime::ENCODING_QUOTEDPRINTABLE;

        array_unshift($attachments, $messageText);
        $message =  new MimeMessage();
        $message->setParts($attachments);
        return $this->body = $message;
    }

    public function getBodyText()
    {
        $body = $this->getBody();
        return parent::getBodyText();
    }
}
