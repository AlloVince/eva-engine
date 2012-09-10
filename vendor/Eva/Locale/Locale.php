<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

 namespace Eva\Locale;

 /**
 * Base class for localization
 *
 * @category  Zend
 * @package   Zend_Locale
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
 class Locale
 {
     /**
     * Class wide Locale Constants
     *
     * @var array $_localeData
     */
     private static $_localeData = array(
         'root'  => true, 'aa_DJ' => true, 'aa_ER' => true, 'aa_ET' => true, 'aa'    => true,
         'af_NA' => true, 'af_ZA' => true, 'af'    => true, 'ak_GH' => true, 'ak'    => true,
         'am_ET' => true, 'am'    => true, 'ar_AE' => true, 'ar_BH' => true, 'ar_DZ' => true,
         'ar_EG' => true, 'ar_IQ' => true, 'ar_JO' => true, 'ar_KW' => true, 'ar_LB' => true,
         'ar_LY' => true, 'ar_MA' => true, 'ar_OM' => true, 'ar_QA' => true, 'ar_SA' => true,
         'ar_SD' => true, 'ar_SY' => true, 'ar_TN' => true, 'ar_YE' => true, 'ar'    => true,
         'as_IN' => true, 'as'    => true, 'az_AZ' => true, 'az'    => true, 'be_BY' => true,
         'be'    => true, 'bg_BG' => true, 'bg'    => true, 'bn_BD' => true, 'bn_IN' => true,
         'bn'    => true, 'bo_CN' => true, 'bo_IN' => true, 'bo'    => true, 'bs_BA' => true,
         'bs'    => true, 'byn_ER'=> true, 'byn'   => true, 'ca_ES' => true, 'ca'    => true,
         'cch_NG'=> true, 'cch'   => true, 'cop'   => true, 'cs_CZ' => true, 'cs'    => true,
         'cy_GB' => true, 'cy'    => true, 'da_DK' => true, 'da'    => true, 'de_AT' => true,
         'de_BE' => true, 'de_CH' => true, 'de_DE' => true, 'de_LI' => true, 'de_LU' => true,
         'de'    => true, 'dv_MV' => true, 'dv'    => true, 'dz_BT' => true, 'dz'    => true,
         'ee_GH' => true, 'ee_TG' => true, 'ee'    => true, 'el_CY' => true, 'el_GR' => true,
         'el'    => true, 'en_AS' => true, 'en_AU' => true, 'en_BE' => true, 'en_BW' => true,
         'en_BZ' => true, 'en_CA' => true, 'en_GB' => true, 'en_GU' => true, 'en_HK' => true,
         'en_IE' => true, 'en_IN' => true, 'en_JM' => true, 'en_MH' => true, 'en_MP' => true,
         'en_MT' => true, 'en_NA' => true, 'en_NZ' => true, 'en_PH' => true, 'en_PK' => true,
         'en_SG' => true, 'en_TT' => true, 'en_UM' => true, 'en_US' => true, 'en_VI' => true,
         'en_ZA' => true, 'en_ZW' => true, 'en'    => true, 'eo'    => true, 'es_AR' => true,
         'es_BO' => true, 'es_CL' => true, 'es_CO' => true, 'es_CR' => true, 'es_DO' => true,
         'es_EC' => true, 'es_ES' => true, 'es_GT' => true, 'es_HN' => true, 'es_MX' => true,
         'es_NI' => true, 'es_PA' => true, 'es_PE' => true, 'es_PR' => true, 'es_PY' => true,
         'es_SV' => true, 'es_US' => true, 'es_UY' => true, 'es_VE' => true, 'es'    => true,
         'et_EE' => true, 'et'    => true, 'eu_ES' => true, 'eu'    => true, 'fa_AF' => true,
         'fa_IR' => true, 'fa'    => true, 'fi_FI' => true, 'fi'    => true, 'fil_PH'=> true,
         'fil'   => true, 'fo_FO' => true, 'fo'    => true, 'fr_BE' => true, 'fr_CA' => true,
         'fr_CH' => true, 'fr_FR' => true, 'fr_LU' => true, 'fr_MC' => true, 'fr_SN' => true,
         'fr'    => true, 'fur_IT'=> true, 'fur'   => true, 'ga_IE' => true, 'ga'    => true,
         'gaa_GH'=> true, 'gaa'   => true, 'gez_ER'=> true, 'gez_ET'=> true, 'gez'   => true,
         'gl_ES' => true, 'gl'    => true, 'gsw_CH'=> true, 'gsw'   => true, 'gu_IN' => true,
         'gu'    => true, 'gv_GB' => true, 'gv'    => true, 'ha_GH' => true, 'ha_NE' => true,
         'ha_NG' => true, 'ha_SD' => true, 'ha'    => true, 'haw_US'=> true, 'haw'   => true,
         'he_IL' => true, 'he'    => true, 'hi_IN' => true, 'hi'    => true, 'hr_HR' => true,
         'hr'    => true, 'hu_HU' => true, 'hu'    => true, 'hy_AM' => true, 'hy'    => true,
         'ia'    => true, 'id_ID' => true, 'id'    => true, 'ig_NG' => true, 'ig'    => true,
         'ii_CN' => true, 'ii'    => true, 'in'    => true, 'is_IS' => true, 'is'    => true,
         'it_CH' => true, 'it_IT' => true, 'it'    => true, 'iu'    => true, 'iw'    => true,
         'ja_JP' => true, 'ja'    => true, 'ka_GE' => true, 'ka'    => true, 'kaj_NG'=> true,
         'kaj'   => true, 'kam_KE'=> true, 'kam'   => true, 'kcg_NG'=> true, 'kcg'   => true,
         'kfo_CI'=> true, 'kfo'   => true, 'kk_KZ' => true, 'kk'    => true, 'kl_GL' => true,
         'kl'    => true, 'km_KH' => true, 'km'    => true, 'kn_IN' => true, 'kn'    => true,
         'ko_KR' => true, 'ko'    => true, 'kok_IN'=> true, 'kok'   => true, 'kpe_GN'=> true,
         'kpe_LR'=> true, 'kpe'   => true, 'ku_IQ' => true, 'ku_IR' => true, 'ku_SY' => true,
         'ku_TR' => true, 'ku'    => true, 'kw_GB' => true, 'kw'    => true, 'ky_KG' => true,
         'ky'    => true, 'ln_CD' => true, 'ln_CG' => true, 'ln'    => true, 'lo_LA' => true,
         'lo'    => true, 'lt_LT' => true, 'lt'    => true, 'lv_LV' => true, 'lv'    => true,
         'mk_MK' => true, 'mk'    => true, 'ml_IN' => true, 'ml'    => true, 'mn_CN' => true,
         'mn_MN' => true, 'mn'    => true, 'mo'    => true, 'mr_IN' => true, 'mr'    => true,
         'ms_BN' => true, 'ms_MY' => true, 'ms'    => true, 'mt_MT' => true, 'mt'    => true,
         'my_MM' => true, 'my'    => true, 'nb_NO' => true, 'nb'    => true, 'nds_DE'=> true,
         'nds'   => true, 'ne_IN' => true, 'ne_NP' => true, 'ne'    => true, 'nl_BE' => true,
         'nl_NL' => true, 'nl'    => true, 'nn_NO' => true, 'nn'    => true, 'no'    => true,
         'nr_ZA' => true, 'nr'    => true, 'nso_ZA'=> true, 'nso'   => true, 'ny_MW' => true,
         'ny'    => true, 'oc_FR' => true, 'oc'    => true, 'om_ET' => true, 'om_KE' => true,
         'om'    => true, 'or_IN' => true, 'or'    => true, 'pa_IN' => true, 'pa_PK' => true,
         'pa'    => true, 'pl_PL' => true, 'pl'    => true, 'ps_AF' => true, 'ps'    => true,
         'pt_BR' => true, 'pt_PT' => true, 'pt'    => true, 'ro_MD' => true, 'ro_RO' => true,
         'ro'    => true, 'ru_RU' => true, 'ru_UA' => true, 'ru'    => true, 'rw_RW' => true,
         'rw'    => true, 'sa_IN' => true, 'sa'    => true, 'se_FI' => true, 'se_NO' => true,
         'se'    => true, 'sh_BA' => true, 'sh_CS' => true, 'sh_YU' => true, 'sh'    => true,
         'si_LK' => true, 'si'    => true, 'sid_ET'=> true, 'sid'   => true, 'sk_SK' => true,
         'sk'    => true, 'sl_SI' => true, 'sl'    => true, 'so_DJ' => true, 'so_ET' => true,
         'so_KE' => true, 'so_SO' => true, 'so'    => true, 'sq_AL' => true, 'sq'    => true,
         'sr_BA' => true, 'sr_CS' => true, 'sr_ME' => true, 'sr_RS' => true, 'sr_YU' => true,
         'sr'    => true, 'ss_SZ' => true, 'ss_ZA' => true, 'ss'    => true, 'st_LS' => true,
         'st_ZA' => true, 'st'    => true, 'sv_FI' => true, 'sv_SE' => true, 'sv'    => true,
         'sw_KE' => true, 'sw_TZ' => true, 'sw'    => true, 'syr_SY'=> true, 'syr'   => true,
         'ta_IN' => true, 'ta'    => true, 'te_IN' => true, 'te'    => true, 'tg_TJ' => true,
         'tg'    => true, 'th_TH' => true, 'th'    => true, 'ti_ER' => true, 'ti_ET' => true,
         'ti'    => true, 'tig_ER'=> true, 'tig'   => true, 'tl'    => true, 'tn_ZA' => true,
         'tn'    => true, 'to_TO' => true, 'to'    => true, 'tr_TR' => true, 'tr'    => true,
         'trv_TW'=> true, 'trv'   => true, 'ts_ZA' => true, 'ts'    => true, 'tt_RU' => true,
         'tt'    => true, 'ug_CN' => true, 'ug'    => true, 'uk_UA' => true, 'uk'    => true,
         'ur_IN' => true, 'ur_PK' => true, 'ur'    => true, 'uz_AF' => true, 'uz_UZ' => true,
         'uz'    => true, 've_ZA' => true, 've'    => true, 'vi_VN' => true, 'vi'    => true,
         'wal_ET'=> true, 'wal'   => true, 'wo_SN' => true, 'wo'    => true, 'xh_ZA' => true,
         'xh'    => true, 'yo_NG' => true, 'yo'    => true, 'zh_CN' => true, 'zh_HK' => true,
         'zh_MO' => true, 'zh_SG' => true, 'zh_TW' => true, 'zh'    => true, 'zu_ZA' => true,
         'zu'    => true
     );

     /**
     * Class wide Locale Constants
     *
     * @var array $_territoryData
     */
     private static $_territoryData = array(
         'AD' => 'ca_AD', 'AE' => 'ar_AE', 'AF' => 'fa_AF', 'AG' => 'en_AG', 'AI' => 'en_AI',
         'AL' => 'sq_AL', 'AM' => 'hy_AM', 'AN' => 'pap_AN', 'AO' => 'pt_AO', 'AQ' => 'und_AQ',
         'AR' => 'es_AR', 'AS' => 'sm_AS', 'AT' => 'de_AT', 'AU' => 'en_AU', 'AW' => 'nl_AW',
         'AX' => 'sv_AX', 'AZ' => 'az_Latn_AZ', 'BA' => 'bs_BA', 'BB' => 'en_BB', 'BD' => 'bn_BD',
         'BE' => 'nl_BE', 'BF' => 'mos_BF', 'BG' => 'bg_BG', 'BH' => 'ar_BH', 'BI' => 'rn_BI',
         'BJ' => 'fr_BJ', 'BL' => 'fr_BL', 'BM' => 'en_BM', 'BN' => 'ms_BN', 'BO' => 'es_BO',
         'BR' => 'pt_BR', 'BS' => 'en_BS', 'BT' => 'dz_BT', 'BV' => 'und_BV', 'BW' => 'en_BW',
         'BY' => 'be_BY', 'BZ' => 'en_BZ', 'CA' => 'en_CA', 'CC' => 'ms_CC', 'CD' => 'sw_CD',
         'CF' => 'fr_CF', 'CG' => 'fr_CG', 'CH' => 'de_CH', 'CI' => 'fr_CI', 'CK' => 'en_CK',
         'CL' => 'es_CL', 'CM' => 'fr_CM', 'CN' => 'zh_Hans_CN', 'CO' => 'es_CO', 'CR' => 'es_CR',
         'CU' => 'es_CU', 'CV' => 'kea_CV', 'CX' => 'en_CX', 'CY' => 'el_CY', 'CZ' => 'cs_CZ',
         'DE' => 'de_DE', 'DJ' => 'aa_DJ', 'DK' => 'da_DK', 'DM' => 'en_DM', 'DO' => 'es_DO',
         'DZ' => 'ar_DZ', 'EC' => 'es_EC', 'EE' => 'et_EE', 'EG' => 'ar_EG', 'EH' => 'ar_EH',
         'ER' => 'ti_ER', 'ES' => 'es_ES', 'ET' => 'en_ET', 'FI' => 'fi_FI', 'FJ' => 'hi_FJ',
         'FK' => 'en_FK', 'FM' => 'chk_FM', 'FO' => 'fo_FO', 'FR' => 'fr_FR', 'GA' => 'fr_GA',
         'GB' => 'en_GB', 'GD' => 'en_GD', 'GE' => 'ka_GE', 'GF' => 'fr_GF', 'GG' => 'en_GG',
         'GH' => 'ak_GH', 'GI' => 'en_GI', 'GL' => 'iu_GL', 'GM' => 'en_GM', 'GN' => 'fr_GN',
         'GP' => 'fr_GP', 'GQ' => 'fan_GQ', 'GR' => 'el_GR', 'GS' => 'und_GS', 'GT' => 'es_GT',
         'GU' => 'en_GU', 'GW' => 'pt_GW', 'GY' => 'en_GY', 'HK' => 'zh_Hant_HK', 'HM' => 'und_HM',
         'HN' => 'es_HN', 'HR' => 'hr_HR', 'HT' => 'ht_HT', 'HU' => 'hu_HU', 'ID' => 'id_ID',
         'IE' => 'en_IE', 'IL' => 'he_IL', 'IM' => 'en_IM', 'IN' => 'hi_IN', 'IO' => 'und_IO',
         'IQ' => 'ar_IQ', 'IR' => 'fa_IR', 'IS' => 'is_IS', 'IT' => 'it_IT', 'JE' => 'en_JE',
         'JM' => 'en_JM', 'JO' => 'ar_JO', 'JP' => 'ja_JP', 'KE' => 'en_KE', 'KG' => 'ky_Cyrl_KG',
         'KH' => 'km_KH', 'KI' => 'en_KI', 'KM' => 'ar_KM', 'KN' => 'en_KN', 'KP' => 'ko_KP',
         'KR' => 'ko_KR', 'KW' => 'ar_KW', 'KY' => 'en_KY', 'KZ' => 'ru_KZ', 'LA' => 'lo_LA',
         'LB' => 'ar_LB', 'LC' => 'en_LC', 'LI' => 'de_LI', 'LK' => 'si_LK', 'LR' => 'en_LR',
         'LS' => 'st_LS', 'LT' => 'lt_LT', 'LU' => 'fr_LU', 'LV' => 'lv_LV', 'LY' => 'ar_LY',
         'MA' => 'ar_MA', 'MC' => 'fr_MC', 'MD' => 'ro_MD', 'ME' => 'sr_Latn_ME', 'MF' => 'fr_MF',
         'MG' => 'mg_MG', 'MH' => 'mh_MH', 'MK' => 'mk_MK', 'ML' => 'bm_ML', 'MM' => 'my_MM',
         'MN' => 'mn_Cyrl_MN', 'MO' => 'zh_Hant_MO', 'MP' => 'en_MP', 'MQ' => 'fr_MQ', 'MR' => 'ar_MR',
         'MS' => 'en_MS', 'MT' => 'mt_MT', 'MU' => 'mfe_MU', 'MV' => 'dv_MV', 'MW' => 'ny_MW',
         'MX' => 'es_MX', 'MY' => 'ms_MY', 'MZ' => 'pt_MZ', 'NA' => 'kj_NA', 'NC' => 'fr_NC',
         'NE' => 'ha_Latn_NE', 'NF' => 'en_NF', 'NG' => 'en_NG', 'NI' => 'es_NI', 'NL' => 'nl_NL',
         'NO' => 'nb_NO', 'NP' => 'ne_NP', 'NR' => 'en_NR', 'NU' => 'niu_NU', 'NZ' => 'en_NZ',
         'OM' => 'ar_OM', 'PA' => 'es_PA', 'PE' => 'es_PE', 'PF' => 'fr_PF', 'PG' => 'tpi_PG',
         'PH' => 'fil_PH', 'PK' => 'ur_PK', 'PL' => 'pl_PL', 'PM' => 'fr_PM', 'PN' => 'en_PN',
         'PR' => 'es_PR', 'PS' => 'ar_PS', 'PT' => 'pt_PT', 'PW' => 'pau_PW', 'PY' => 'gn_PY',
         'QA' => 'ar_QA', 'RE' => 'fr_RE', 'RO' => 'ro_RO', 'RS' => 'sr_Cyrl_RS', 'RU' => 'ru_RU',
         'RW' => 'rw_RW', 'SA' => 'ar_SA', 'SB' => 'en_SB', 'SC' => 'crs_SC', 'SD' => 'ar_SD',
         'SE' => 'sv_SE', 'SG' => 'en_SG', 'SH' => 'en_SH', 'SI' => 'sl_SI', 'SJ' => 'nb_SJ',
         'SK' => 'sk_SK', 'SL' => 'kri_SL', 'SM' => 'it_SM', 'SN' => 'fr_SN', 'SO' => 'sw_SO',
         'SR' => 'srn_SR', 'ST' => 'pt_ST', 'SV' => 'es_SV', 'SY' => 'ar_SY', 'SZ' => 'en_SZ',
         'TC' => 'en_TC', 'TD' => 'fr_TD', 'TF' => 'und_TF', 'TG' => 'fr_TG', 'TH' => 'th_TH',
         'TJ' => 'tg_Cyrl_TJ', 'TK' => 'tkl_TK', 'TL' => 'pt_TL', 'TM' => 'tk_TM', 'TN' => 'ar_TN',
         'TO' => 'to_TO', 'TR' => 'tr_TR', 'TT' => 'en_TT', 'TV' => 'tvl_TV', 'TW' => 'zh_Hant_TW',
         'TZ' => 'sw_TZ', 'UA' => 'uk_UA', 'UG' => 'sw_UG', 'UM' => 'en_UM', 'US' => 'en_US',
         'UY' => 'es_UY', 'UZ' => 'uz_Cyrl_UZ', 'VA' => 'it_VA', 'VC' => 'en_VC', 'VE' => 'es_VE',
         'VG' => 'en_VG', 'VI' => 'en_VI', 'VU' => 'bi_VU', 'WF' => 'wls_WF', 'WS' => 'sm_WS',
         'YE' => 'ar_YE', 'YT' => 'swb_YT', 'ZA' => 'en_ZA', 'ZM' => 'en_ZM', 'ZW' => 'sn_ZW'
     );

     /**
     * Autosearch constants
     */
     const BROWSER     = 'browser';
     const ENVIRONMENT = 'environment';
     const ZFDEFAULT   = 'default';

     /**
     * Defines if old behaviour should be supported
     * Old behaviour throws notices and will be deleted in future releases
     *
     * @var boolean
     */
     public static $compatibilityMode = false;

     /**
     * Internal variable
     *
     * @var boolean
     */
     private static $_breakChain = false;

     /**
     * Actual set locale
     *
     * @var string Locale
     */
     protected $_locale;

     /**
     * Automatic detected locale
     *
     * @var string Locales
     */
     protected static $_auto;

     /**
     * Browser detected locale
     *
     * @var string Locales
     */
     protected static $_browser;

     /**
     * Environment detected locale
     *
     * @var string Locales
     */
     protected static $_environment;

     /**
     * Default locale
     *
     * @var string Locales
     */
     protected static $_default = array('en' => true);


     /**
     * Return an array of all accepted languages of the client
     * Expects RFC compilant Header !!
     *
     * The notation can be :
     * de,en-UK-US;q=0.5,fr-FR;q=0.2
     *
     * @return array - list of accepted languages including quality
     */
     public static function getBrowser()
     {
         if (self::$_browser !== null) {
             return self::$_browser;
         }

         $httplanguages = getenv('HTTP_ACCEPT_LANGUAGE');
         if (empty($httplanguages) && array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
             $httplanguages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
         }

         $languages     = array();
         if (empty($httplanguages)) {
             return $languages;
         }

         $accepted = preg_split('/,\s*/', $httplanguages);

         foreach ($accepted as $accept) {
             $match  = null;
             $result = preg_match('/^([a-z]{1,8}(?:[-_][a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i',
             $accept, $match);

             if ($result < 1) {
                 continue;
             }

             if (isset($match[2]) === true) {
                 $quality = (float) $match[2];
             } else {
                 $quality = 1.0;
             }

             $countrys = explode('-', $match[1]);
             $region   = array_shift($countrys);

             $country2 = explode('_', $region);
             $region   = array_shift($country2);

             foreach ($countrys as $country) {
                 $languages[$region . '_' . strtoupper($country)] = $quality;
             }

             foreach ($country2 as $country) {
                 $languages[$region . '_' . strtoupper($country)] = $quality;
             }

             if ((isset($languages[$region]) === false) || ($languages[$region] < $quality)) {
                 $languages[$region] = $quality;
             }
         }

         self::$_browser = $languages;
         return $languages;
     }
 }
