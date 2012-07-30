<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Filter
 */

namespace Eva\Filter\File;

use Traversable;
use Zend\Filter;
use Zend\Filter\Exception;
use Zend\Stdlib\ArrayUtils;

/**
 * @category   Zend
 * @package    Zend_Filter
 */
class AutoRename extends \Zend\Filter\AbstractFilter
{
    protected $rootpath;
    protected $pathkey;
    protected $pathlevel;

    /**
     * Class constructor
     *
     * Options argument may be either a string, a Zend_Config object, or an array.
     * If an array or Zend_Config object, it accepts the following keys:
     * 'source'    => Source filename or directory which will be renamed
     * 'target'    => Target filename or directory, the new name of the source file
     * 'overwrite' => Shall existing files be overwritten ?
     *
     * @param  string|array|Traversable $options Target file or directory to be renamed
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($options)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException('Invalid options argument provided to filter');
        }

        $defaultOptions = array(
            'pathkey' => '',
            'rootpath' => '',
            'pathlevel' => 0,
        );
        $this->setOptions(array_merge($defaultOptions, $options));
    }

    public function setPathkey($pathkey = null)
    {
        if(!$pathkey){
            $pathkey = 'default';
        }

        $config = \Eva\Api::_()->getConfig();
        if(isset($config['upload']['storage'][$pathkey])){
            $config = $config['upload']['storage'][$pathkey];
            if(isset($config['rootpath'])){
                $this->rootpath = $config['rootpath'];
            }

            if(isset($config['pathlevel'])){
                $this->pathlevel = $config['pathlevel'];
            }
        }

        $this->pathkey = $pathkey;
        return $this;
    }


    public function setRootpath($rootpath = null)
    {
        if($rootpath){
            $this->rootpath = $rootpath;
        }

        $rootpath = $this->rootpath;

        if (!$rootpath) {
            throw new Exception\RuntimeException(sprintf("File storage root path %s not set", $rootpath));
        }

        if(false === file_exists($rootpath)){
            throw new Exception\RuntimeException(sprintf("File storage root path %s is not an exist folder", $rootpath));
        }

        return $this;
    }



    public function setPathlevel($pathlevel = null)
    {
        if($pathlevel > 0){
            $this->pathlevel = $pathlevel;
        } else {
            if(!$this->pathlevel){
                $this->pathlevel = 0;
            }
        }
        return $this;
    }


    /**
     * Returns only the new filename without moving it
     * But existing files will be erased when the overwrite option is true
     *
     * @param  string  $value  Full path of file to change
     * @param  boolean $source Return internal informations
     * @return string The new filename which has been set
     * @throws Exception\InvalidArgumentException If the target file already exists.
     */
    public function getNewName($value, $source = false, $fileinfo)
    {
        $rootpath = $this->rootpath;
        $path = $this->getPath();


        $filename = \Eva\Stdlib\String\Hash::uniqueHash();

        $fileextension = '';
        if(isset($fileinfo['name'])){
            $fileextension = '.' . $this->getExtension($fileinfo['name']);
        }
        $filepath = $rootpath . \DIRECTORY_SEPARATOR . $path . $filename . $fileextension;
        return $filepath;
    }

    protected function getPath()
    {
        $uniquId = uniqid('', true);
        $hash = md5($uniquId);
        $level = $this->pathlevel;
        $path = '';

        if($level > 10){
            throw new Exception\RuntimeException(sprintf("File storage path level %s is over limit, max level is 10.", $level));
        }

        if ($level > 0) {
            for ($i = 0, $max = ($level * 2); $i < $max; $i+= 2) {
                $path .= $hash[$i] . $hash[$i+1] . \DIRECTORY_SEPARATOR;
            }
        }
        return $path;
    }

    /**
     * Get file extension 
     *
     * @access public
     * @param string $name  file name string
     *
     * @return string file extension
     */ 
    protected function getExtension($fileFullName)
    {
        return strtolower(end(explode(".", $fileFullName)));
    }

    /**
     * Defined by Zend\Filter\Filter
     *
     * Renames the file $value to the new name set before
     * Returns the file $value, removing all but digit characters
     *
     * @param  string $value Full path of file to change
     * @throws Exception\RuntimeException
     * @return string The new filename which has been set, or false when there were errors
     */
    public function filter($value)
    {
        $file   = $this->getNewName($value, true);
        if (is_string($file)) {
            return $file;
        }

        $result = rename($file['source'], $file['target']);

        if ($result !== true) {
            throw new Exception\RuntimeException(sprintf("File '%s' could not be renamed. An error occured while processing the file.", $value));
        }

        return $file['target'];
    }


}
