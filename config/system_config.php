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

// layouts
define('LAYOUTS', APP_PATH.'/layouts');
define('LAYOUTS_FRONT',  '/frontend');
define('LAYOUTS_BACK', '/backend');

//templates
define('TEMPLATES', APP_PATH.'/templates/');
define('TEMPLATES_FRONT', TEMPLATES.'/frontend');
define('TEMPLATES_BACK', TEMPLATES.'/backend');
define('TEMPLATES_FRONT_DEFAULT', TEMPLATES_FRONT.'/default');
define('TEMPLATES_BACK_DEFAULT', TEMPLATES_BACK.'/default');


define('LOG_FILE', BASE_PATH.'/log/system_error_log.log');

// urls
define('SECURE_URL', 'https://'.$_SERVER['SERVER_NAME']);
define('UNSECURE_URL', 'http://'.$_SERVER['SERVER_NAME']);

define('PREV_PREFIX', 'prev:');
define('SEP', '-');
define('DOB_SEP','.');

define('CSS', UNSECURE_URL.'/public/css');
define('JS', UNSECURE_URL.'/public/js');