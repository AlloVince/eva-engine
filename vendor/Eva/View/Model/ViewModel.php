<?php
namespace Eva\View\Model;

class ViewModel extends \Zend\View\Model\ViewModel
{
    protected $layoutRenderStopFlag = false;

    public function getLayoutRenderStopFlag()
    {
        return $this->layoutRenderStopFlag;
    }

    public function stopLayoutRender()
    {
        $this->layoutRenderStopFlag = true;
        return $this;
    }
}
