<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once 'PHPUnit/Framework.php';

define('PATH_TO_CLASSES', realpath(dirname(__FILE__).'/../classes/'));

function import($className)
{
    $classPath = "/".str_replace('_','/',$className.'.php');
    $potentialPaths = array(
        PATH_TO_CLASSES.$classPath,
    );
    foreach ($potentialPaths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
    throw new Exception("Cannot find $className");
}

spl_autoload_register('import');
