<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Authentication
 */

namespace Eva\Authentication\Adapter;

use Zend\Authentication\Result as AuthenticationResult;
use Zend\Authentication\Exception;

/**
 * @category   Zend
 * @package    Zend_Authentication
 * @subpackage Adapter
 */
class Config implements \Zend\Authentication\Adapter\AdapterInterface
{
    /**
     * Filename against which authentication queries are performed
     *
     * @var string
     */
    protected $configArray;

    /**
     * Digest authentication user
     *
     * @var string
     */
    protected $username;

    /**
     * Password for the user of the realm
     *
     * @var string
     */
    protected $password;

    /**
     * Sets adapter options
     *
     * @param  mixed $filename
     * @param  mixed $realm
     * @param  mixed $username
     * @param  mixed $password
     */
    public function __construct($configArray = null, $username = null, $password = null)
    {
        $options = array('configArray', 'username', 'password');
        foreach ($options as $option) {
            if (null !== $$option) {
                $methodName = 'set' . ucfirst($option);
                $this->$methodName($$option);
            }
        }
    }

    /**
     * Returns the filename option value or null if it has not yet been set
     *
     * @return string|null
     */
    public function getConfigArray()
    {
        return $this->configArray;
    }

    /**
     * Sets the filename option value
     *
     * @param  mixed $filename
     * @return Digest Provides a fluent interface
     */
    public function setConfigArray(array $configArray)
    {
        $this->configArray = $configArray;
        return $this;
    }


    /**
     * Returns the username option value or null if it has not yet been set
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the username option value
     *
     * @param  mixed $username
     * @return Digest Provides a fluent interface
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;
        return $this;
    }

    /**
     * Returns the password option value or null if it has not yet been set
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password option value
     *
     * @param  mixed $password
     * @return Digest Provides a fluent interface
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
        return $this;
    }

    /**
     * Defined by Zend\Authentication\Adapter\AdapterInterface
     *
     * @throws Exception\ExceptionInterface
     * @return AuthenticationResult
     */
    public function authenticate()
    {
        $optionsRequired = array('configArray', 'username', 'password');
        foreach ($optionsRequired as $optionRequired) {
            if (null === $this->$optionRequired) {
                throw new Exception\RuntimeException("Option '$optionRequired' must be set before authentication");
            }
        }

        $result = array(
            'code'  => AuthenticationResult::FAILURE,
            'identity' => array(
                'password'    => $this->password,
                'username' => $this->username,
            ),
            'messages' => array()
        );

        $configArray = $this->configArray;

        if($configArray['username'] == $this->username && $configArray['password'] == $this->password) {
            $result['code'] = AuthenticationResult::SUCCESS;
            return new AuthenticationResult($result['code'], $result['identity'], $result['messages']);
        }

        if($configArray['username'] != $this->username){
            $result['code'] = AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND;
            $result['messages'][] = "Username '$this->username' not found";
            return new AuthenticationResult($result['code'], $result['identity'], $result['messages']);
        }

        if($configArray['username'] == $this->username && $configArray['password'] != $this->password){
            $result['code'] = AuthenticationResult::FAILURE_CREDENTIAL_INVALID;
            $result['messages'][] = 'Password incorrect';
        }
        return new AuthenticationResult($result['code'], $result['identity'], $result['messages']);
    }
}
