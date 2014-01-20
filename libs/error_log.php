<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 19/01/14
 * Time: 14:16
 */

class ErrorLog {

    private $logFile = LOG_FILE;
    private $exception;
    private $webmasterEmail = WEBMASTER_EMAIL;

    public function __construct($e){
        $this->exception = $e;
    }

    public function createLog(){
        $message = '['.date('c')."]\r\n";
        $message .= "Error message: {$this->exception->getMessage()} \r\n";
        $message .= "File: {$this->exception->getFile()} \r\n";
        $message .= "Line: {$this->exception->getLine()} \r\n";
        $message .= "*\r\n";
        error_log($message, 3, $this->logFile);
        $this->sendEmail($message);
    }

    private function sendEmail($message){
        mail($this->webmasterEmail, 'NFIS Error Report', $message);
    }

} 