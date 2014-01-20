<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 10/01/2014
 * Time: 13:47
 */

class FormHelper {

    private $inputs;
    private $errors;

    public function __construct($initData){
        $this->inputs = $initData['inputs'];
        $this->errors = $initData['errors'];

    }



    public function getFieldValue($fieldName,  $default = ''){
        return (isset($this->inputs[$fieldName]))? $this->inputs[$fieldName]: $default;
    }

    public function getFieldError($fieldName){
        return (isset($this->errors[$fieldName]))? $this->errors[$fieldName]: '';
    }

    public function setValueForTextElement(&$element, $defalut = ''){
        $element['value'] = $this->getFieldValue($element['name'], $defalut);
    }

    public function setValuesForTextElements(&$elements){
        foreach ($elements as $key => $element) {
            $this->setValueForTextElement($elements[$key]);
        }
    }

    public function setValueForSelectElement(&$element){
        //var_dump($element);
        $element['value']['selected'] = $this->getFieldValue($element['name']);

    }

    public function setValuesForSelectElements(&$elements){
        foreach ($elements as $key => $element) {
            $this->setValueForSelectElement($elements[$key]);
        }
    }

    public function setErrorForElement(&$element){
        $element['options']['error'] = $this->getFieldError($element['name']);
    }

    public function setErrorsForElements(&$elements){
        foreach ($elements as $key => $element) {
            $this->setErrorForElement($elements[$key]);
        }
    }

} 