<?php
namespace Epic\Controller;

use Eva\Api,
    Core\Session,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel;

class LanguageController extends ActionController
{
    public function switchAction()
    {
        $config = Api::_()->getConfig();
        $defaultLanguage = isset($config['translator']['locale']) ? $config['translator']['locale'] : 'en';
        $language = $this->params('id', $defaultLanguage);
        $language = str_replace('-', '_', $language);
        $languages = $config['translator']['languages'];
        if(false === in_array($language, $languages)){
            $language = $defaultLanguage;
        }

        $callback = @$_SERVER['HTTP_REFERER'];
        $callback = $callback ? $callback : '/';

        $this->cookie()->write('lang', $language, 3600*24*365);


        $this->redirect()->toUrl($callback);
        $model = new ViewModel();
        $model->setTemplate('blank');
        return $model;
    }
}
