<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_OAuth
 */

namespace Oauth\Service\Token;

/**
 * @category   Zend
 * @package    Zend_OAuth
 */
abstract class AbstractToken extends \ZendOAuth\Token\AbstractToken
{
    /**@+
     * Token constants
     */
    const TOKEN_PARAM_KEY                = 'code';
    const TOKEN_SECRET_PARAM_KEY         = 'state';
    const TOKEN_PARAM_CALLBACK_CONFIRMED = 'oauth_callback_confirmed';
    /**@-*/
}
