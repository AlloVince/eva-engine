<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Authentication
 */

namespace Activity\Service;


use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Video\Service\LinkParser;

/**
* @category   Activity
* @package    Activity
*/
class TextParser
{
    protected $users = array();

    protected $links = array();

    protected $videos = array();

    protected $html;

    protected $isParsed = false;

    protected $options;

    protected $twitterOptions = array(
        'userUrl' => 'http://twitter.com/%s',
        'sharpUrl' => 'http://search.twitter.com/search?q=%23%s',
        'sharpStyle' => 'begin',
    );

    protected $weiboOptions = array(
        'userUrl' => 'http://weibo.com/%s',
        'sharpUrl' => 'http://s.weibo.com/weibo/%s',
        'sharpStyle' => 'wrap',
    );

    protected $text;

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    /**
    * Constructor
    *
    */
    public static function factory($text, array $options, ServiceLocatorInterface $serviceLocator = null)
    {
        $parser = new static();
        $parser->setText($text);
        if($serviceLocator){
            $parser->setServiceLocator($serviceLocator);
        }
        $parser->setOptions($options);
        return $parser;
    }


    /**
    * Set the service locator.
    *
    * @param ServiceLocatorInterface $serviceLocator
    * @return AbstractHelper
    */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
    * Get the service locator.
    *
    * @return \Zend\ServiceManager\ServiceLocatorInterface
    */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $defaultOptions = array(
            'userUrl' => '',
            'sharpUrl' => '',
            'urlTarget' => '',
            'sharpStyle' => 'begin' //begin  will parse #topic | wrap will parse #topic# 

        );
        if($serviceLocator = $this->getServiceLocator()){
            $config = $serviceLocator->get('config');
            $defaultOptions['userUrl'] = $config['activity']['userUrl'];
            $defaultOptions['sharpUrl'] = $config['activity']['sharpUrl'];
            $defaultOptions['urlTarget'] = $config['activity']['urlTarget'];
        }
        $options = array_merge($defaultOptions, $options);
        $this->options = $options;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getUserNames()
    {
        $text = trim($this->getText());
        if(!$text){
            return $this->users;
        }

        $matches = array();
        preg_match_all('/@([^\s]+)/', $text, $matches);
        if(isset($matches[1]) && $matches[1]){
            $this->users = array_unique($matches[1]);
        }
        return $this->users;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getVideos()
    {
        $this->parse();
        if(!$this->links){
            return $this->videos;
        }

        $videos = array();
        foreach($this->links as $url) {
            $video = LinkParser::factory($url);
            if($video->isValid()){
                $videos[] = array(
                    'url' => $video->toString(),
                    'swf' => $video->getSwfUrl(),
                    'thumbnail' => $video->getThumbnail(),
                    'width' => $video->getPlayerWidth(),
                    'height' => $video->getPlayerHeight(),
                    'remoteId' => $video->getRemoteId(),
                );
            }
        }

        return $this->videos = $videos;
    }

    public function getVideo()
    {
        $videos = $this->getVideos();
        if($videos){
            return $videos[0];
        }
    }

    public function getHtml()
    {
        $this->parse();
        return $this->html;
    }

    public function parse()
    {
        if(true === $this->isParsed) {
            return $this->html;
        }

        $text = trim($this->getText());
        if(!$text){
            $this->isParsed = true;
            return '';
        }

        preg_match_all('@(https?://([-\w\.]+)+(/([\w/_\.-]*(\?\S+)?(#\S+)?)?)?)@', $text, $matches);
        if($matches && isset($matches[0][0])){
            $this->links = $matches[0];
        }

        $options = $this->getOptions();
        $urlTarget = $options['urlTarget'] ? ' target="' .  $options['urlTarget']. '"'  : '';

        $text = preg_replace('@(https?://([-\w\.]+)+(/([\w/_\.-]*(\?\S+)?(#\S+)?)?)?)@',
        '<a href="$1" ' . $urlTarget . '>$1</a>', $text);

        $userUrl = sprintf($options['userUrl'], '$1');
        $text = preg_replace('/@(\w+)/',
        '<a href="' . $userUrl .'" '.  $urlTarget .'>@$1</a>', $text);


        $sharpUrl = sprintf($options['sharpUrl'], '$1');
        $sharpStyle = $options['sharpStyle'];
        $text = preg_replace('/\s+#(\w+)/',
        ' <a href="' . $sharpUrl . '" ' . $urlTarget . '>#$1</a>', $text);

        $this->isParsed = true;
        return $this->html = $text;
    }
}
