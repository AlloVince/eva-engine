<?php
namespace Avnpc\Controller;

use Zend\Http\Client,
    Eva\Mvc\Controller\ActionController,
    Zend\Json\Json,
    Zend\View\Model\JsonModel;

class ProxyController extends ActionController
{

    public function indexAction()
    {
        //Use spreadsheets in url will set time to google docs
        //fix js source with search "spreadsheets"
        $page = 'https://spreadsheets.google.com/feeds/list/0Ag9Yy1IHOFGtdFdMRmpyVWliRE96SXBLVnlvOXI1OUE/od6/public/values?alt=json';
        $client = new Client($page, array(
            'maxredirects' => 0,
            'sslverifypeer' => false,
            'timeout'      => 5
        ));

        $response = $client->send();
        $responseText = $response->getBody();

        /*
        $view = new JsonModel((array) Json::decode($responseText));
        $view->setTerminal(true);
        $view->setTemplate('blank');
        //$this->pagecapture();
        return $view;
        */
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($responseText);
        $this->pagecapture();
        return $response;
    }
}
