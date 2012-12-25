<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Blog\Helper;

use Zend\View\Exception;


/**
 * View helper for rendering Form objects
 * 
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class DisqusComment extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     * 
     * @param  ElementInterface $element 
     * @return string
     */
    public function __invoke($postIdOrUrl = null, $options = array())
    {
        $config = $this->getView()->getHelperPluginManager()->getServiceLocator()->get('config');
        $defaultConfig = array(
            'websiteId' => '',
        );
        if(isset($config['blog']['comment_social']['duoshuo'])){
            $defaultConfig = array_merge($defaultConfig, $config['blog']['comment_social']['disqus']);
        }
        $options = array_merge($defaultConfig, $options);
        if(!$options['websiteId']){
            throw new Exception\InvalidArgumentException(sprintf(
                'No website ID found in disqus comment helper'
            ));
        }

        return
<<<"CODE"
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        var disqus_shortname = 'avnpc';
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
CODE;
    }
}
