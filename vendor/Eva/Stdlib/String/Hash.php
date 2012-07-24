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

 namespace Eva\Stdlib\String;

 /**
 * Unique Hash
 * From : http://blog.kevburnsjr.com/php-unique-hash
 * 
 * @category  Eva
 * @package   Eva_Stdlib
 */
 class Hash
 {
     public static function guid()
     {
         if (function_exists('com_create_guid') === true)
         {
             return trim(com_create_guid(), '{}');
         }
         return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
     }


     public static function shortHashArray($input) {
         $base32 =  array(
             "a" , "b" , "c" , "d" , "e" , "f" , "g" , "h" ,  
             "i" , "j" , "k" , "l" , "m" , "n" , "o" , "p" ,  
             "q" , "r" , "s" , "t" , "u" , "v" , "w" , "x" ,  
             "y" , "z" , "0" , "1" , "2" , "3" , "4" , "5" ,  
             "6" , "7" , "8" , "9" , "A" , "B" , "C" , "D" ,  
             "E" , "F" , "G" , "H" , "I" , "J" , "K" , "L" ,  
             "M" , "N" , "O" , "P" , "Q" , "R" , "S" , "T" ,  
             "U" , "V" , "W" , "X" , "Y" , "Z"  
         );  


         $hex = md5($input);
         $hexLen = strlen($hex);
         $subHexLen = $hexLen / 8;
         $output = array();

         for ($i = 0; $i < $subHexLen; $i++) {
             $subHex = substr ($hex, $i * 8, 8);
             $int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
             $out = '';

             for ($j = 0; $j < 6; $j++) {
                 $val = 0x0000003D & $int;
                 $out .= $base32[$val];
                 $int = $int >> 5;
             }

             $output[] = $out;
         }

         return $output;
     }


     public static function uniqueHash()
     {
         $guid = self::guid();
         $guid = str_replace('-', '', $guid);

         $hashArray = self::shortHashArray($guid);
         return $hashArray[rand(0, 3)];
     }

     /**
     * Translates a number to a short alhanumeric version
     *
     * Translated any number up to 9007199254740992
     * to a shorter version in letters e.g.:
     * 9007199254740989 --> PpQXn7COf
     * @author	Kevin van Zonneveld <kevin@vanzonneveld.net>
     * @author	Simon Franz
     * @author	Deadfish
     * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
     * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
     * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
     * @link	  http://kevin.vanzonneveld.net/
     *
     * @param mixed   $in	  String or long input to translate
     * @param boolean $toNum  Reverses translation when true
     * @param mixed   $padUp  Number or boolean padds the result up to a specified length
     * @param string  $passKey Supplying a password makes it harder to calculate the original ID
     *
     * @return mixed string or long
     */
     public static function shortHash($in = null, $toNum = false, $padUp = false, $passKey = null)
     {
         if(!$in){
             $in = mt_rand();
             $padUp = 6;
         }

         $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
         if ($passKey !== null) {
             // Although this function's purpose is to just make the
             // ID short - and not so much secure,
             // with this patch by Simon Franz (http://blog.snaky.org/)
             // you can optionally supply a password to make it harder
             // to calculate the corresponding numeric ID

             for ($n = 0; $n<strlen($index); $n++) {
                 $i[] = substr( $index,$n ,1);
             }

             $passhash = hash('sha256',$passKey);
             $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512',$passKey) : $passhash;

             for ($n=0; $n < strlen($index); $n++) {
                 $p[] =  substr($passhash, $n ,1);
             }

             array_multisort($p,  SORT_DESC, $i);
             $index = implode($i);
         }

         $base  = strlen($index);

         if ($toNum) {
             // Digital number  <<--  alphabet letter code
             $in  = strrev($in);
             $out = 0;
             $len = strlen($in) - 1;
             for ($t = 0; $t <= $len; $t++) {
                 $bcpow = bcpow($base, $len - $t);
                 $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
             }

             if (is_numeric($padUp)) {
                 $padUp--;
                 if ($padUp > 0) {
                     $out -= pow($base, $padUp);
                 }
             }
             $out = sprintf('%F', $out);
             $out = substr($out, 0, strpos($out, '.'));
         } else {
             // Digital number  -->>  alphabet letter code
             if (is_numeric($padUp)) {
                 $padUp--;
                 if ($padUp > 0) {
                     $in += pow($base, $padUp);
                 }
             }

             $out = "";
             for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                 $bcp = bcpow($base, $t);
                 $a   = floor($in / $bcp) % $base;
                 $out = $out . substr($index, $a, 1);
                 $in  = $in - ($a * $bcp);
             }
             $out = strrev($out); // reverse
         }

         return $out;
     }

 }
