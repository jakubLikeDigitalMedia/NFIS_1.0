<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 19/01/14
 * Time: 14:03
 */

function app_error_handler(
    $in_errno,
    $in_errstr,
    $in_errfile,
    $in_errline,
    $in_errcontext
)
{
    $customErrorMsg = 'We are sory , but an unexpected error has occured during performing your action.';
    throw new SystemException($customErrorMsg, $in_errstr, $in_errfile, $in_errline);
}

function app_exception_handler($exception){
    //die(var_dump($exception));
    $errorLog = new ErrorLog($exception);
    $errorLog->createLog();
    // redirect to error page
    echo '<h1>Error</h1>';
    echo "<p>{$exception->getMessage()}</br>File: <pre>{$exception->getFile()}</pre></br>Line: <pre>{$exception->getLine()}</pre></p>";

}

class DatabaseErrorException extends Exception
{
    function __construct($in_errmsg, $query = NULL)
    {
        parent::__construct("We're sorry, but an internal database error has occurred. Our system administrators have been notified and we kindly request that you try again in a little while.  Thank you for your patience. ($in_errmsg) \n SQL query $query");
    }
}

set_error_handler('app_error_handler');
set_exception_handler('app_exception_handler');
