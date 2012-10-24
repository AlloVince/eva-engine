<?php
namespace Video\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Video\Service\LinkParser;

class VideoController extends AbstractActionController
{
    public function indexAction()
    {
        $url = 'http://v.youku.com/v_show/id_XNDY2MDE0NzA0.html?f=18450607';
        $url = 'http://www.youtube.com/watch?v=HDd55pneMUg&feature=g-all-esi';
        $url = 'http://youtu.be/HDd55pneMUg';
        $video = LinkParser::factory($url);

        if($video->isValid()){
            $video->getSwfUrl();
        }

        $view = new ViewModel(array(
            'video' => $video
        ));
        return $view;
    }
}
