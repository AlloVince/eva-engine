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

namespace Eva\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\View\Exception;

/**
* Render View Partial Cross Module
* 
* @category   Eva
* @package    Eva_View
* @subpackage Helper
* @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
* @license    http://framework.zend.com/license/new-bsd     New BSD License
*/
class GoogleAnalytics extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * Variable to which object will be assigned
     * @var string
     */
    protected $objectKey;


    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    /**
    * Set the service locator.
    *
    * @param ServiceLocatorInterface $serviceLocator
    * @return AbstractHelper
    */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }



    public function __invoke($code = null, $domainType = 'single', $domainName = null)
    {
        if(!$code){
            $config = $this->serviceLocator->getServiceLocator()->get('Configuration');
            $code = isset($config['site']['google_analytics']['code']) ? $config['site']['google_analytics']['code'] : '';
            $domain = isset($config['site']['google_analytics']['domain']) ? $config['site']['google_analytics']['domain'] : '';
        }
        if(!$code){
            return '<!--No Google Analytics Code Found-->';
        }
        switch($domainType){
            case 'multidomain':
            return "<script type=\"text/javascript\">var _gaq=_gaq||[];_gaq.push(['_setAccount','$code']);_gaq.push(['_setDomainName','$domainName']);_gaq.push(['_setAllowLinker',true]);_gaq.push(['_trackPageview']);(function(){var ga=document.createElement('script');ga.type='text/javascript';ga.async=true;ga.src=('https:'==document.location.protocol?'https://ssl':'http://www')+'.google-analytics.com/ga.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s)})();</script>";
            case 'subdomain':
            return "<script type=\"text/javascript\">var _gaq=_gaq||[];_gaq.push(['_setAccount','$code']);_gaq.push(['_setDomainName','$domainName']);_gaq.push(['_trackPageview']);(function(){var ga=document.createElement('script');ga.type='text/javascript';ga.async=true;ga.src=('https:'==document.location.protocol?'https://ssl':'http://www')+'.google-analytics.com/ga.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s)})();</script>";
            default:
            return "<script type=\"text/javascript\">var _gaq=_gaq||[];_gaq.push(['_setAccount','$code']);_gaq.push(['_trackPageview']);(function(){var ga=document.createElement('script');ga.type='text/javascript';ga.async=true;ga.src=('https:'==document.location.protocol?'https://ssl':'http://www')+'.google-analytics.com/ga.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga,s)})();</script>";
        }

    }

}
