<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 18/01/14
 * Time: 16:52
 */

if( !empty($_POST['_method']) && in_array($_POST['_method'], array('put', 'delete')) ) {
    $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
}

// main routing system
include_once 'controller.php';
include_once 'router.php';
include_once 'sammy.php';
include_once 'action_link.php';

//DB connection details
include_once CONFIG.'/db_connection.php';

//load models
include_once 'load_models.php';

// error handling
include_once 'system_exception.php';
include_once 'error_log.php';
include_once 'error_handlers.php';

// helper libs
include_once 'dbmanager.php';
include_once 'query_handler.php';
include_once 'htmlgen.php';
include_once 'gump.class.php';
include_once 'input_validator.php';
include_once 'form_generator.php';
include_once 'form_helper.php';

include_once CONFIG.'/routes.php';