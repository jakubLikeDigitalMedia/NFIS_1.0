<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 18/01/14
 * Time: 16:49
 */

// error reporting set for development
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASE_PATH', dirname(realpath(__FILE__)));
define('APP_PATH', BASE_PATH.'/app');
define('CONFIG', BASE_PATH.'/config');

include_once CONFIG.'/system_config.php';
include_once LIBS.'/core.php';

$sammy = Sammy::instance();
$sammy->run();




