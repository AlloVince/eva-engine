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

 namespace Eva\Stdlib\Arraylib;

 /**
 * Unique Hash
 * From : http://blog.kevburnsjr.com/php-unique-hash
 * 
 * @category  Eva
 * @package   Eva_Stdlib
 */
 class Sort
 {
     /**
     * Sort an two-dimension array by some level two items use array_multisort() function.
     *
     * sysSortArray($Array,"Key1","SORT_ASC","SORT_RETULAR","Key2")
     * @author Chunsheng Wang <wwccss@263.net>
     * @param array $arrayData the array to sort.
     * @param string $keyName the first item to sort by.
     * @param string $sortOrder the order to sort by("SORT_ASC"|"SORT_DESC")
     * @param string $sortType the sort type("SORT_REGULAR"|"SORT_NUMERIC"|"SORT_STRING")
     * @return array sorted array.
     */ 
    public static function multiSortArray($arrayData, $keyName, $sortOrder = "SORT_ASC", $sortType = "SORT_REGULAR")
    {
        if (!is_array($arrayData)) {
            return $arrayData;
        }

        // Get args number.
        $argCount = func_num_args();

        // Get keys to sort by and put them to SortRule array.
        for($i = 1;$i < $argCount;$i ++)
        {
            $arg = func_get_arg($i);
            if(!preg_match("/SORT/",$arg)){
                $keyNameList[] = $arg;
                $sortRule[] = '$'.$arg;
            }else{
                $sortRule[] = $arg;
            }
        }

        // Get the values according to the keys and put them to array.
        foreach($arrayData as $key => $info){
            foreach($keyNameList AS $keyName)
            {
                ${$keyName}[$key] = $info[$keyName];
            }
        }

        // Create the eval string and eval it.
        $evalString = 'array_multisort(' . join(",", $sortRule) . ',$arrayData);';
        eval ($evalString);
        return $arrayData;
	}

 }
