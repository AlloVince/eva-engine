<?php
namespace Commission\Service;

use Eva\Api,
    Core\Auth,
    Eva\View\Model\ViewModel;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Json;

class Yiqifa
{
    public function getProduct($url)
	{
        $stores = $this->getCpsData();
        $urlArray = parse_url($url);

        $hostMap = array(
            'item.51buy.com' => 'www.51buy.com',
        );
        $host = $urlArray['host'];
        if(isset($hostMap[$host])){
            $host = $hostMap[$host];
        }

        if(!isset($stores[$host])){
            return false;
        }

        $store = $stores[$host];
        $cps = $store['cps'];
        $cps['query']['t'] = $url;
        $store['click_url'] = 'http://p.yiqifa.com/c?' . urldecode(http_build_query($cps['query']));
        $store['pic_url'] = 'http://placekitten.com/g/100/100';
        $store['title'] = 'Affiliates Link';
        $store['item_location'] = '';
        $store['price'] = '';
        $store['commission_rate'] = '';
        return $store;
    }

    protected function getCpsData()
    {
        $cpsFile = EVA_ROOT_PATH . '/data/databases/taoke.csv';

        if(false === file_exists($cpsFile)){
            return;
        }

        $stores = array();
        if (($handle = fopen($cpsFile, "r")) !== false) {
            while (($cell = $this->fgetcsv($handle)) !== false) {
                if(!isset($cell[0])){
                    continue;
                }

                $cpsUrl = isset($cell[11]) ? $cell[11] : '';
                if(!$cpsUrl){
                    continue;
                }

                $cpsUrl = parse_url($cpsUrl);
                $host = isset($cpsUrl['host']) ? $cpsUrl['host'] : 0;
                if($host){
                    parse_str($cpsUrl['query'], $cpsParams);
                    $cpsUrl['query'] = $cpsParams;
                    $storeUrl = $cpsParams['t'];
                    $storeUrl = parse_url($storeUrl);
                    $storeHost = isset($storeUrl['host']) ? $storeUrl['host'] : 0;
                    $stores[$storeHost] = array(
                        'nick' => @iconv('GB2312', 'UTF-8', $cell[1]),
                        'commission' => @iconv('GB2312', 'UTF-8', $cell[3]),
                        'shop_click_url' => @$cell[11],
                        'index_page_commission_only' => @$cell[9] == 'yes' ? false : true,
                        'cps' => $cpsUrl,
                    );
                }

            }
            fclose($handle);
        }

        return $stores;
    }


    protected function fgetcsv(& $handle, $length = null, $d = ',', $e = '"') 
    {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $eof=false;
        while ($eof != true) {
            $_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
            if ($itemcnt % 2 == 0)
                $eof = true;
        }
        $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
        $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) {
            $_csv_data[$_csv_i] = preg_replace('/^' . $e . '(.*)' . $e . '$/s', '$1' , $_csv_data[$_csv_i]);
            $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
        }
        return empty ($_line) ? false : $_csv_data;
    }
}
