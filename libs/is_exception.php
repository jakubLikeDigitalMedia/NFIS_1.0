<?php

class IS_Exception extends Exception{

	private $customMessage;

	public function __construct($customMessageType, $phpMessage = NULL, $file = NULL, $line = NULL){
		parent::__construct($phpMessage);

        switch ($customMessageType){
            case 'session': $this->customMessage = 'Session Expired';
                break;
            case 'no_template': $this->customMessage = 'Can\'t Load template.';
        }

		// change parent properties if needed
		$this->file = ($file)? $file: $this->file;
		$this->line = ($line)? $line: $this->line;

	}

	public function getCustomMessage(){
		return $this->customMessage;
	}



}
