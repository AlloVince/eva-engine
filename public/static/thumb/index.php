<?php
/**
 * EvaCloudImage
 * light-weight url based image transformation php library
 *
 * @link      https://github.com/AlloVince/EvaCloudImage
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @author    AlloVince
 */

error_reporting(E_ALL);
// Check php version
if( version_compare(phpversion(), '5.3.0', '<') ) {
    printf('PHP 5.3.0 is required, you have %s', phpversion());
    exit(1);
}

$config = include __DIR__ . '/config.inc.php';
$configLocalFile = __DIR__ . '/../../../config/front/image.config.php';
$configLocal = is_file($configLocalFile) ? include $configLocalFile : array();
$config = $configLocal ? array_merge($config, $configLocal) : $config;

include $config['classPath'];

$cloudImage = new EvaCloudImage(null, $config);
$cloudImage->show();
