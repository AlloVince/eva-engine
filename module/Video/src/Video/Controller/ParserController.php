<?php
namespace Video\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Video\Service\LinkParser;

class ParserController extends AbstractActionController
{
    public function indexAction()
    {
        $url = $this->params()->fromQuery('url');
        $this->changeviewmodel('json');

        $video = LinkParser::factory($url);

        if(!$video->isValid()){
            return new JsonModel(array(
                'remoteId' => null,
            ));
        }

        $view = new JsonModel(array(
            'remoteId' => $video->getRemoteId(),
            'swf' => $video->getSwfUrl(),
            'url' => $video->toString(),
            'thumnail' => $video->getThumbnail(),
        ));
        return $view;
    }
}
