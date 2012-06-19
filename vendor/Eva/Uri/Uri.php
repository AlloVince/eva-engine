<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Uri
 */

namespace Eva\Uri;

/**
 * Generic URI handler
 *
 * @category  Zend
 * @package   Zend_Uri
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class Uri extends \Zend\Uri\Uri
{
    protected $basePath;
    protected $basePathAdded = false;
    protected $callbackName;
    protected $callback;
    protected $version;
    protected $versionName = 'v';
    protected $versionAdded = false;
    protected $baseQuery;
    protected $baseQueryAdded = false;

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
        return $this;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    public function getCallbackName()
    {
        return $this->callbackName;
    }

    public function setCallbackName($callbackName)
    {
        $this->callbackName = $callbackName;
        return $this;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersionName($versionName)
    {
        $this->versionName = $versionName;
        return $this;
    }

    public function getVersionName()
    {
        return $this->versionName;
    }

    public function getBaseQuery()
    {
        return $this->baseQuery;
    }

    public function setBaseQuery($baseQuery)
    {
        $this->baseQuery = $baseQuery;
        return $this;
    }


    public function addBasePath($basePath = '')
    {
        if(true === $this->basePathAdded){
            return $this;
        }

        $path = $this->getPath();
        $basePath = $basePath ? $basePath : $this->getBasePath();
        if(!$basePath){
            return $this;
        }

        $this->setBasePath($basePath);
        $path = $basePath . $path;
        $this->setPath($path);

        $this->basePathAdded = true;

        return $this;
    }

    public function addVersion($version = '')
    {
        if(true === $this->versionAdded){
            return $this;
        }

        $version = (string) $version;
        $this->version = $version;
    
        $query = $this->getQueryAsArray();

        $versionName = $this->getVersionName();

        if(!$versionName || empty($version)){
            return $this;
        }

        if(isset($query[$versionName])){
            throw new \Zend\Uri\Exception\InvalidUriPartException(sprintf(
                'Version Name "%s" is already taken',
                $versionName,
                get_class($this)
            ), Exception\InvalidUriPartException::INVALID_SCHEME);
        }

        $query[$versionName] = $version;
        $this->setQuery($query);
        $this->versionAdded = true;
        return $this;
    }

    public function addBaseQuery(array $baseQuery = array())
    {
        if(true === $this->baseQueryAdded){
            return $this;
        }

        $baseQuery = $baseQuery ? $baseQuery : $this->getBaseQuery();
        if(!$baseQuery){
            return $this;
        }
        $query = $this->getQueryAsArray();
        $this->setBaseQuery($baseQuery);

        $query = array_merge($baseQuery, $query);
        $this->setQuery($query);

        $this->baseQueryAdded = true;
        return $this;
    }

    public function toUrlEncodeString($url = '')
    {
        $url = $url ? $url : $this->toString();
        if(!$url){
            return '';
        }
        return urlencode($url);
    }

    public function toHtmlEncodeString($url = '')
    {
        $url = $url ? $url : $this->toString();
        if(!$url){
            return '';
        }
        return htmlentities($url, ENT_QUOTES, 'UTF-8');
    }
}
