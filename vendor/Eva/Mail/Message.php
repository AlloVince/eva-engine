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
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;

/**
 * @category   Zend
 * @package    Zend_Mail
 */
class Message extends \Zend\Mail\Message
{

    const VIEW_PATH_NAME  = 'defaultPath';

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

    /**
     * Get the string-serialized message body text
     *
     * @return string
     */
    public function getBodyText()
    {
        if(!$this->template){
            return parent::getBodyText();
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

        //p($this->view);
        //$this->view->setTemplate($this->template);
        //$this->view->setVariables($this->data);
        //p($view->render($viewModel));
        return $view->render($viewModel);
    }
}
