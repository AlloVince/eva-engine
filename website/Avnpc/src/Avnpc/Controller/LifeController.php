<?php
namespace Avnpc\Controller;

use Eva\Api,
    Eva\Mvc\Controller\ActionController,
    Eva\View\Model\ViewModel,
    Zend\Feed\Reader\Reader as FeedReader;

class LifeController extends ActionController
{
    protected $addResources = array(
    );

    protected $_lastfmCache = array();

    public function indexAction()
    {
		$url = 'https://www.google.com/reader/public/atom/user%2F06943440676883415375%2Flabel%2FLife?r=n&n=30';
        $id = $this->params()->fromRoute('id');
        if($id) {
            $url .= '&c=' . $id;
        }

        $cache = null;
		if ($cache && $cacheData = $cache->load($cacheKey)) {
			$data = $cacheData['data'];
			$continuation = $cacheData['nextpage'];
		} else {
            $httpClient = FeedReader::getHttpClient();
            $httpClient->setOptions(array(
                'sslverifypeer' => false
            ));
            $feed = FeedReader::import($url);
			$feed->getXpath()->registerNamespace(
				'gr', 'http://example.com/junglebooks/rss/module/1.0/'
			);
			$continuation = $feed->getXpath()->evaluate('string(' . $feed->getXpathPrefix() . '/gr:continuation)');

			$data = array();
			foreach ($feed as $entry) {
				$edata = array(
					'title'        => $entry->getTitle(),
                    'dateModified' => $entry->getDateModified()->format(DATE_ISO8601), //$entry->getDateModified()->toString(\Zend\Date::ISO_8601),
					'link'         => $entry->getLink(),
					'content'      => $entry->getContent()
				);
                $edata = $this->entryHandler($edata);
                if($edata){
                    $data[] = $edata;
                }
			}
			if($cache){
			}
        }
        $data = \Eva\Stdlib\Arraylib\Sort::multiSortArray($data, 'dateModified', 'SORT_DESC');

        $view = new ViewModel(array(
            'feeds' => $data,
            'nextpage' => $continuation,
            'nextId' => $id,
        ));
        $view->setTemplate('avnpc/life/index');
        $this->pagecapture();
        return $view;
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
			case 'weibo.com':
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

        if(!$entry){
            return false;
        }

		$entry['type'] = $type;
		$entry['source'] = $source;
		return $entry;
	}

	protected function _googleplus($entry)
	{
		$html = new \simple_html_dom();
		$html->load($entry['content']);

		$links = $html->find('a[href*=googleusercontent.com]');
		if($links){
			$entry['largeImage'] = true;
		}
		foreach($links as $key => $link){
            $link->innertext = '<img src="' . $link->href .'" width="100%"  itemprop="photo" />';
		}
		

		$entry['content'] = (string) $html;
		return $entry;
	}

	protected function _weibo($entry)
	{
        $text = strip_tags($entry['content']);
        if(false !== strpos($text, '@')){
            return '';
        }
		$html = new \simple_html_dom();
		$html->load($entry['content']);

		$imgs = $html->find('img[src*=sinaimg.cn]');
		foreach($imgs as $key => $img){
			$img->src = str_replace('thumbnail', 'bmiddle', $img->src);
			$img->width = '100%';
            $img->itemprop = 'photo';
		}
		$retweet = $html->find('span', 0);
		if($retweet){
			$retweet->outertext = '<blockquote>' . $retweet->innertext . '</blockquote>';
		}

        $ps = $html->find('p > p');
        foreach($ps as $key => $p){
            if(0 === strpos($p->innertext, '分享到: ')){
                $p->outertext = '';
            }
        }

		$entry['content'] = (string) $html;
        $entry['content'] = preg_replace('/来自:[^<]+<br>/', '', $entry['content']);
		return $entry;
	}

	protected function _douban($entry)
	{
		$html = new \simple_html_dom();
		$html->load($entry['content']);

		$links = $html->find('a[href*=movie.douban.com]');
		if($links){
			$entry['largeImage'] = true;
		}
		foreach($links as $key => $link){
			$img = $link->find('img[src*=douban.com]', 0);
            $link->innertext = '<img src="' . str_replace('spic', 'lpic', $img->src) .'" width="100%"  itemprop="photo" />';
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

        $text = strip_tags($entry['content']);
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
            $cacheFile = EVA_ROOT_PATH . '/data/cache/other/lastfm.php';
            if(file_exists($cacheFile)){
			    $this->_lastfmCache = unserialize(file_get_contents($cacheFile));
            }
		}

		$artist = trim($artist);
		$img = '';
		if($artists = $this->_lastfmCache){
			if($artists[$artist]){
				$img = $artists[$artist];
			}
		}

		if($img){
            $img = '<div class="centeralign"><img src=' . $img . ' class="thumbnail" alt="' . $artist . '"  itemprop="photo" /></div>';
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
