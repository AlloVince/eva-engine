<?php
function p($r)
{
    echo "|";
    print_r($r);
    echo "|";
}

/**
 * Setup autoloading
 */
function ZendTest_Autoloader($class) 
{
    $class = ltrim($class, '\\');

    if (!preg_match('#^(Zend(Test)?|Eva(Test)?|PHPUnit)(\\\\|_)#', $class)) {
        return false;
    }

    // $segments = explode('\\', $class); // preg_split('#\\\\|_#', $class);//
    $segments = preg_split('#[\\\\_]#', $class); // preg_split('#\\\\|_#', $class);//
    $ns       = array_shift($segments);

    switch ($ns) {
        case 'Zend':
            $file = dirname(__DIR__) . '/vendor/Zend/';
            break;
		case 'Eva':
            $file = dirname(__DIR__) . '/vendor/Eva/';
            break;
        case 'ZendTest':
            // temporary fix for ZendTest namespace until we can migrate files 
            // into ZendTest dir
            $file = __DIR__ . '/ZendTest/';
            break;
		case 'EvaTest':
            $file = __DIR__ . '/EvaTest/';
            break;
        default:
            $file = false;
            break;
	}


    if ($file) {
        $file .= implode('/', $segments) . '.php';
        if (file_exists($file)) {
            return include_once $file;
        }
    }

    $segments = explode('_', $class);
    $ns       = array_shift($segments);

    switch ($ns) {
        case 'Zend':
            $file = dirname(__DIR__) . '/vendor/Zend/';
			break;
        case 'Eva':
            $file = dirname(__DIR__) . '/vendor/Eva/';
            break;
        default:
            return false;
    }
    $file .= implode('/', $segments) . '.php';
    if (file_exists($file)) {
        return include_once $file;
    }

    return false;
}
spl_autoload_register('ZendTest_Autoloader', true, true);

