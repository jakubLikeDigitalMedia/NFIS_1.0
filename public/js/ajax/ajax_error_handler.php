<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 19/01/14
 * Time: 14:03
 */

function app_ajax_error_handler(
    $in_errno,
    $in_errstr,
    $in_errfile,
    $in_errline,
    $in_errcontext
)
{
    $customErrorMsg = 'We are sory , but an unexpected error has occured during performing your action.';
    //throw new SystemException($customErrorMsg, $in_errstr, $in_errfile, $in_errline);
    die(json_encode(array('Error message' => $customErrorMsg, 'Error' => $in_errstr, 'Error file' => $in_errfile, 'Error line' => $in_errline)));
}

function app_ajax_exception_handler($exception){
    //die(var_dump($exception));
    $errorLog = new ErrorLog($exception);
    $errorLog->createLog();
    // redirect to error page
    //echo '<h1>Error</h1>';
    //echo "<p>{$exception->getMessage()}</br>File: <pre>{$exception->getFile()}</pre></br>Line: <pre>{$exception->getLine()}</pre></p>";
    die(json_encode(array('Error message' => $exception->getMessage(), 'Error file' => $exception->getFile(), 'Error line' => $exception->getLine())));
}

set_error_handler('app_ajax_error_handler');
set_exception_handler('app_ajax_exception_handler');
