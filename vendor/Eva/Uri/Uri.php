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
	protected $version;
	protected $versionName = 'v';

	public function setVersion($version)
	{
		$this->version = $version;
	}

	public function getVersion()
	{
		return $this->version;
	}

	public function setVersionName($versionName)
	{
		$this->versionName = $versionName;
	}

	public function getVersionName()
	{
		return $this->versionName;
	}

	public function addVersion($version = '')
	{
		$version = (string) $version;
		$this->version = $version;
	
		$query = $this->getQuery();

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

		return $this;
	}
}
