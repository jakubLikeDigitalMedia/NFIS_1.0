<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 18/01/14
 * Time: 19:22
 */

define('WEBMASTER_EMAIL', 'example@gmail.com');

define('LIBS', BASE_PATH.'/libs');

define('CONTROLLERS', APP_PATH.'/controllers');
define('MODELS', APP_PATH.'/models');
define('VIEWS', APP_PATH.'/views');
define('FORMS', APP_PATH.'/forms');
define('LAYOUTS', APP_PATH.'/layouts');
define('LOG_FILE', BASE_PATH.'/log/system_error_log.log');

// urls
define('SECURE_URL', 'https://'.$_SERVER['SERVER_NAME']);
define('UNSECURE_URL', 'http://'.$_SERVER['SERVER_NAME']);

define('PREV_PREFIX', 'prev:');
define('SEP', '-');
define('DOB_SEP','.');

define('CSS', UNSECURE_URL.'/public/css');
define('JS', UNSECURE_URL.'/public/js');