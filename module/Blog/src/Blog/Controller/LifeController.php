<?php
/**
 * Easthv Zend Framework Project
 *
 *
 * LICENSE:
 *
 * @category   Zend
 * @package    Easthv_Controller
 * @copyright  Copyright (c) 2009-2010 Easthv Team @ East Hui Van Business Consulting Co., Ltd.(http://www.easthv.com)
 * @license    http://framework.zend.com/license/   BSD License
 * @version    1.0
 * @link       http://www.easthv.com
 * @since      1.0
*/

/**
 * Index Controller.
 * 
 * @uses       Zend_Easthv_Abstract_Controller
 * @category   Zend
 * @package    Easthv_Controller
 * @subpackage Controller
 * @copyright  Copyright (c) 2009-2010 Easthv Team @ East Hui Van Business Consulting Co., Ltd.(http://www.easthv.com)
 * @license    http://framework.zend.com/license/   BSD License
 * @version    1.0
 * @link       http://www.easthv.com
 * @since      1.0
 * @deprecated
 */
class LifeController extends Zend_Easthv_Abstract_Controller
{

    public function initAll()
    {
        $this->initLayout()
            ->initView()
			->initLanguage();
	}

    /**
     * The index page
     *
     * via the following urls:
     * - /
     *
     * @access public
     *
     * @return void
     */
	public function restIndexLife($nextId = null)
	{
		$url = 'https://www.google.com/reader/public/atom/user%2F06943440676883415375%2Flabel%2FLife?r=n&n=30';
		if($nextId) {
			$url .= '&c=' . $nextId;
		}

		$tweet = new Easthv_Model_Tweet();
		$cacheKey = 'life_' . md5($nextId);
		$cache = $tweet->initQueryCache();
		if ($cache && $cacheData = $cache->load($cacheKey)) {
			$data = $cacheData['data'];
			$continuation = $cacheData['nextpage'];
		} else {
			Zend_Feed::registerNamespace("gr", "http://www.google.com/schemas/reader/atom/");
			$feed = Zend_Feed_Reader::import($url);
			$feed->getXpath()->registerNamespace(
				'gr', 'http://example.com/junglebooks/rss/module/1.0/'
			);
			$continuation = $feed->getXpath()->evaluate('string(' . $feed->getXpathPrefix() . '/gr:continuation)');
			Zend_Date::setOptions(array('format_type' => 'iso')); 

			$data = array();
			foreach ($feed as $entry) {
				$edata = array(
					'title'        => $entry->getTitle(),
					'dateModified' => $entry->getDateModified()->toString(Zend_Date::ISO_8601),
					'link'         => $entry->getLink(),
					'content'      => $entry->getContent()
				);
				$edata = $this->entryHandler($edata);
				$data[] = $edata;
			}

			if($cache){
				$config = Zend_Registry::get('config');
				$cache->setLifetime(3600);
				$cache->save(array(
					'nextpage' => $continuation,
					'data' => $data,	
				), $cacheKey);   		
			}
		}

		$this->assign('title', 'Life of AlloVince');
		$this->assign("feed", $data, true);
		$this->assign("nextpage", $continuation, true);
	}

	public function restGetLife()
	{
		$req = $this->getParams();
		if($req['id']){
			$this->restIndexLife($req['id']);	
		}
	}

	public function restGetLifeRender()
	{
		$this->render('index');
	}

	public function entryHandler($entry)
	{
		if(!$entry['link']){
			return $entry;
		}

		$url = parse_url($entry['link']);
		$host = $url['host'];

		$type = 'tweet';
		$source = '';
		switch($host) {
		case 'plus.google.com':
			$entry = $this->_googleplus($entry);
			$source = 'Google+';
			break;
		case 'api.t.sina.com.cn':
			case 'www.weibo.com':
				$entry = $this->_weibo($entry);
				$source = '微博';
				break;
			case 'twitter.com':
				$entry = $this->_twitter($entry);
				$source = 'Twitter';
				break;
			case 'www.last.fm':
				$entry = $this->_lastfm($entry);
				$source = 'LastFm';
				$type = 'music';
				break;
			case 'www.evernote.com':
				$entry = $this->_evernote($entry);
				$source = 'EverNote';
				$type = 'memo';
				break;
			case 'movie.douban.com':
			case 'book.douban.com':
			case 'music.douban.com':
				$type = 'movie';
				$source = '豆瓣';
				$entry = $this->_douban($entry);
				break;
			default:
				$type = 'note';
				break;
		}

		$entry['type'] = $type;
		$entry['source'] = $source;
		return $entry;
	}

	protected function _googleplus($entry)
	{
		require_once 'simple_html_dom.php';
		$html = new simple_html_dom();
		$html->load($entry['content']);

		$links = $html->find('a[href*=googleusercontent.com]');
		if($links){
			$entry['largeImage'] = true;
		}
		foreach($links as $key => $link){
			$link->innertext = '<img src="' . $link->href .'" width="100%" />';
		}
		

		$entry['content'] = (string) $html;
		return $entry;
	}

	protected function _weibo($entry)
	{
		require_once 'simple_html_dom.php';
		$html = new simple_html_dom();
		$html->load($entry['content']);

		$imgs = $html->find('img[src*=sinaimg.cn]');
		foreach($imgs as $key => $img){
			$img->src = str_replace('thumbnail', 'bmiddle', $img->src);
			$img->width = '100%';
		}
		$retweet = $html->find('span', 0);
		if($retweet){
			$retweet->outertext = '<blockquote>' . $retweet->innertext . '</blockquote>';
		}

		$entry['content'] = (string) $html;

		return $entry;
	
	}

	protected function _douban($entry)
	{
		require_once 'simple_html_dom.php';
		$html = new simple_html_dom();
		$html->load($entry['content']);

		$links = $html->find('a[href*=movie.douban.com]');
		if($links){
			$entry['largeImage'] = true;
		}
		foreach($links as $key => $link){
			$img = $link->find('img[src*=douban.com]', 0);
			$link->innertext = '<img src="' . str_replace('spic', 'lpic', $img->src) .'" width="100%" />';
		}

		$tds = $html->find('td');
		if($tds){
			$content = '';
			foreach($tds as $key => $td){
				$content .= $td->innertext;
			}
			$entry['content'] = $content;
		} else {
			$entry['content'] = (string) $html;

		}


		return $entry;
	}

	protected function _twitter($entry)
	{

		$text = $entry['content'];
		$text = preg_replace("/^(\w+:)/i", "", $text);		
		$text = preg_replace("/(https*:\/\/[a-z0-9_\-\/\.]+)/i", "<a href=\"\${1}\" rel=\"nofollow\">\${1}</a>", $text);		
		$text = preg_replace("/(\@\w+)/i", "<a href='http://twitter.com/\${1}'>\${1}</a>", $text);		
		
		$lines = explode("\n", $text);

		foreach($lines as $key => $line){
			$line = trim($line);
			if(!$line || $line == '&nbsp;'){
				unset($lines[$key]);
			}
		}

		$entry['content'] = '<p>' . implode("</p><p>", $lines) . '</p>';
		
		return $entry;
	}

	protected function _lastfm($entry)
	{
		$title = $entry['title'];
		list($artist, $title) = explode("– ", $title);

		if(!$this->_lastfmCache) {
			$cacheFile = APPLICATION_PATH . '/../public/tmp/lastfm.php';
			$this->_lastfmCache = unserialize(file_get_contents($cacheFile));
		}

		$artist = trim($artist);
		$img = '';
		if($artists = $this->_lastfmCache){
			if($artists[$artist]){
				$img = $artists[$artist];
			}
		}

		if($img){
			$img = '<div class="centeralign"><img src=' . $img . ' class="thumbnail" alt="' . $artist . '" /></div>';
		}

		$entry['title'] = $title;
		$entry['content'] = '来自 : <a href="' . $entry['content'] . '">' . $artist . '</a>' . $img;
		return $entry;
	}

	protected function _evernote($entry)
	{
		return $entry;
	}
}
