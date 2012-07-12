<?php
namespace Core\Admin;

use Core\Exception;


class MultiForm
{
    public static function getPostDataArray($postData, $fieldKey = 'id', $splitMark = ',')
    {
        $postArray = array();
        if(!isset($postData[$fieldKey])){
            return $postArray;
        }

        $idArray = explode($splitMark, $postData[$fieldKey]);
        unset($postData[$fieldKey]);
        if(!$idArray){
            return $postArray;
        }

        $idArrayLen = count($idArray);
        foreach($idArray as $id){
            $postArray[] = array(
                $fieldKey => $id,
            );
        }

        if($postData){
            foreach($postData as $key => $valueString){
                $valueArray = explode($splitMark, $valueString);
                if(count($valueArray) !== $idArrayLen){
                    throw new Exception\InvalidArgumentException(sprintf(
                        'Input array length not match'
                    ));
                }
                foreach($postArray as $i => $array){
                    $postArray[$i][$key] = $valueArray[$i];
                }
            }
        }
        return $postArray;
    }
}
