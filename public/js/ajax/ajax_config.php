<?php
$path = str_replace('public\js\ajax', '', realpath("."));
require($path. 'public/js/ajax/ajax_error_handler.php');
define('BASE_PATH', $path);
define('APP_PATH', BASE_PATH.'/app');
define('CONFIG', BASE_PATH.'/config');
require($path. 'config/system_config.php');
require($path. 'libs/core.php');

include($path. 'public/js/ajax/image/image_create.php');
include($path. 'public/js/ajax/video/video_create.php');