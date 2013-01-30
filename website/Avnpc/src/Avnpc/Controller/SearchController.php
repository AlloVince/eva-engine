<?php
namespace Avnpc\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Json;

class SearchController extends ActionController
{

    public function indexAction()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();

        if(!$this->params()->fromQuery()){
            return array();
        }

        $form = new \Avnpc\Form\SearchForm();
        $form->bind($query);

        $item = array();
        $items = array();
        if($form->isValid()){
            $data = $form->getData();
            $q = $data['q'];
            $validator = new \Zend\Validator\Uri(array(
                'allowRelative' => false,
            ));
            if($validator->isValid($q)){
                $uri = new \Zend\Uri\Uri($q);
                $urlQuery = $uri->getQueryAsArray();
                switch($uri->getHost()){
                    case 'item.taobao.com':
                    case 'detail.tmall.com':

                    if($urlQuery['id']){
                        $item = $this->getTaokeCommission($urlQuery['id']);
                    }

                    break;
                    default:
                    $item = $this->getCommissionLink($q);
                }

            } else {
                $items = $this->googleSearch($q, $data['page']);
            }
        }

        return array(
            'form' => $form,
            'item' => $item,
            'items' => $items,
        );
    }

    protected function googleSearch($q, $page)
    {
        $client = new Client();
        $client->setUri('https://www.googleapis.com/customsearch/v1');
        $query = array(
            'q' => $q,
            'key' => 'AIzaSyBOhMyOU9e5cMIUrZFR4Yr2a32FF1ePfn0',
            'cx' => '005909242293933576388:h9kkzcmu7y8',
            'alt' => 'json',
        );
        $client->setOptions(array(
            'sslverifypeer' => false
        ));
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet($query);

        $response = $client->send();
        $response = $client->send();
		$responseText = $response->getBody();
        $res = Json::decode($responseText, Json::TYPE_ARRAY);

        return isset($res['items']) ? $res['items'] : array();
    }

    protected function getCommissionLink($url)
    {
        $commission = new \Commission\Service\Yiqifa();
        $item = $commission->getProduct($url);
        return $item;
    }

    protected function getTaokeCommission($pid, $nick = null)
    {
        $commission = new \Commission\Service\Taoke();
        $item = $commission->getProduct($pid, $nick);
        return $item;
    }
}
