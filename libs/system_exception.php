<?php

class SystemException extends Exception{

	private $customMessage;

	public function __construct($message, $systemMessage = NULL, $file = NULL, $line = NULL){
        if (empty($systemMessage)) parent::__construct($this->getErrorMessage($message));
		else parent::__construct($systemMessage);
		$this->customMessage = $this->getErrorMessage($message);
		// change parent properties if needed
		$this->file = ($file)? $file: $this->file;
		$this->line = ($line)? $line: $this->line;

	}

	public function getCustomMessage(){
		return $this->customMessage;
	}


    private function getErrorMessage($message){
        if (is_string($message)) return $message;
        else{
            switch ($message){
                case 'db':
                    return 'Database Error';
            }
        }
    }

}




