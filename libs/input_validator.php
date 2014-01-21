<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 17/12/2013
 * Time: 17:44
 */

class InputValidator extends GUMP{

    private $predefRules = array(
        'name' => 'required|alpha_with_space|max_len,128',
        'title' => 'required|alpha_with_space|max_len,128',
        'email' => 'required|valid_email',
        'DOB' => 'required|DOB',
        'intNoZeroReq' => 'required|int_no_zero',
        'street' => 'required|alpha_numeric_with_space|max_len,255',
        'postcode' => 'required|alpha_numeric_with_space|max_len,128',
        'alphaNumLen255' => 'alpha_with_space|max_len,255'
    );

    private $errorMessages = array(
        'validate_alpha' => 'Only letters are allowed',
        'validate_alpha_with_space' => 'Only letters are allowed',
        'validate_DOB' => 'Invalid date format',
        'validate_street_address' => 'Only alphanumeric characters with spaces are allowed',
        'validate_alpha_numeric' => 'Only alphanumeric characters are allowed',
        'validate_alpha_numeric_with_space' => 'Only alphanumeric characters with spaces are allowed',
        'validate_phone_num' => 'Enter your phone number in following format +477 123 456 789',
        'validate_valid_email' => 'Invalid email format',
        'validate_required' => 'This field is required',
        'validate_integer' => 'Value must be integer',
        'validate_max_len' => 'Value or string must be max ? characters long',
        'validate_int_no_zero' => 'Select value',
        'validate_unique' => 'This title already exists'
    );

    /*
     * Custom validator extensions
     */
    public function validate_phone_num(){

    }

    public function validate_date_of_birth(){

    }

    public function validate_alpha_with_space($field, $input, $param = NULL){

        if(!isset($input[$field])|| empty($input[$field]))
        {
            return;
        }

        $passes = preg_match('/^[a-zA-Z\s]+$/', $input[$field]);

        if(!$passes) {
            return array(
                'field' => $field,
                'value' => $input[$field],
                'rule'	=> __FUNCTION__,
                'param' => $param
            );
        }

    }

    public function validate_alpha_numeric_with_space($field, $input, $param = NULL){

        if(!isset($input[$field])|| empty($input[$field]))
        {
            return;
        }

        $passes = preg_match('/[a-zA-Z\s\d]+/', $input[$field]);

        if(!$passes) return $this->getResultArray($field, $input[$field], __FUNCTION__, $param);

    }

    public function validate_int_no_zero($field, $input, $param = NULL){

        if(!isset($input[$field])|| empty($input[$field]))
        {
            return;
        }

        $passes = ($input[$field] !== 0 );

        if(!$passes) return $this->getResultArray($field, $input[$field], __FUNCTION__, $param);

    }

    public function validate_DOB($field, $input, $param = NULL){

        if(!isset($input[$field]) || empty($input[$field]))
        {
            return;
        }

        $passes = preg_match('/\d{1,2}\.\d{1,2}\.\d{4}/', $input[$field]);

        if(!$passes) return $this->getResultArray($field, $input[$field], __FUNCTION__, $param);

    }

    public function validate_unique($field, $input, $param = NULL){

        if(!isset($input[$field]) || empty($input[$field]))
        {
            return;
        }

        $model = new $param();
        if ($model->recordExist($model::TITLE,$input[$field]))
            return $this->getResultArray($field, $input[$field], __FUNCTION__, $param);

    }

    private function getResultArray($field, $value, $func, $param){
        return array(
            'field' => $field,
            'value' => $value,
            'rule'	=> $func,
            'param' => $param
        );

    }

    private function getErrors($employeeDetails, $validationRules){
        $validationResult = $this->validate($employeeDetails, $validationRules);
        //die(var_dump($validationResult));
        if (!is_array($validationResult)) return NULL;
        else{
            $errorMessages = array();
            foreach ($validationResult as $resultField) {
                $errorMessages[$resultField['field']] = (!empty($resultField['param']))? str_replace('?', $resultField['param'], $this->errorMessages[$resultField['rule']]): $errorMessages[$resultField['field']] = $this->errorMessages[$resultField['rule']];
                $errorMessages[$resultField['field']] = "<b>{$errorMessages[$resultField['field']]}</b>";
            }
            return $errorMessages;
        }
    }

    public function validateEmployee($employeeDetails){
        $employee = new Employee_Model();
        $employment = new Employment_Model();
        $address = new Address_Model();

        $validationRules = array(
            $employee->getTableCollumnName($employee::NAME) => $this->predefRules['name'],
            $employee->getTableCollumnName($employee::SURNAME) => $this->predefRules['name'],
            $employee->getTableCollumnName($employee::DOB) => $this->predefRules['DOB'],
            $employee->getTableCollumnName($employee::EMAIL) => $this->predefRules['email'],
            $employee->getTableCollumnName($employee::PHONE_NUMBER) => 'required',
            $employment->getTableCollumnName($employment::POSITION) => $this->predefRules['intNoZeroReq'],
            $employment->getTableCollumnName($employment::BRAND) => $this->predefRules['intNoZeroReq'],
            $employment->getTableCollumnName($employment::LOCATION) => $this->predefRules['intNoZeroReq'],
            $employment->getTableCollumnName($employment::DEPARTMENT) => $this->predefRules['intNoZeroReq'],
            $employee->getTableCollumnName($employee::PARENT) => 'integer',
            $address->getTableCollumnName($address::STREET) => $this->predefRules['street'],
            $address->getTableCollumnName($address::POSTCODE) => $this->predefRules['postcode'],
            $address->getTableCollumnName($address::CITY) => 'required|'.$this->predefRules['alphaNumLen255'],
            $address->getTableCollumnName($address::COUNTY) => $this->predefRules['alphaNumLen255'],
        );

        return $this->getErrors($employeeDetails, $validationRules);

    }

    public function validateEmployeePrevEmpl($employeeDetails){

        $employment = new Employment_Model();
        $address = new Address_Model();

        $validationRules = array(
            PREV_PREFIX.$employment->getTableCollumnName($employment::POSITION) => $this->predefRules['intNoZeroReq'],
            PREV_PREFIX.$employment->getTableCollumnName($employment::BRAND) => $this->predefRules['intNoZeroReq'],
            PREV_PREFIX.$employment->getTableCollumnName($employment::LOCATION) => $this->predefRules['intNoZeroReq'],
            PREV_PREFIX.$employment->getTableCollumnName($employment::DEPARTMENT) => $this->predefRules['intNoZeroReq'],
            PREV_PREFIX.$address->getTableCollumnName($address::STREET) => $this->predefRules['street'],
            PREV_PREFIX.$address->getTableCollumnName($address::POSTCODE) => $this->predefRules['postcode'],
            PREV_PREFIX.$address->getTableCollumnName($address::CITY) => 'required|'.$this->predefRules['alphaNumLen255'],
            PREV_PREFIX.$address->getTableCollumnName($address::COUNTY) => $this->predefRules['alphaNumLen255'],
        );

        return $this->getErrors($employeeDetails, $validationRules);

    }

    public function validateGroup($groupDetails){

        $validationRules = array(
            Group_Model::TITLE => $this->predefRules['title'].'|unique,Group_Model' ,
        );

        return $this->getErrors($groupDetails, $validationRules);

    }





} 