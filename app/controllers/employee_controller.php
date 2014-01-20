<?php

class Employee_Controller extends App_Controller{

    public function index(){
        echo 'index action';
    }

    public function created(){

    }

    public function add() {

    }

    public function create() {
        //die('create');
        $result = $this->createAccount($_POST);
        //die(var_dump($result));
        if (!empty($result['current_empl']) OR (!empty($result['prev_empl']))){

            session_start();
            $_SESSION['user']['errors']['current_empl'] = $result['current_empl'];
            $_SESSION['user']['inputs']['current_empl'] = $_POST;
            $_SESSION['user']['errors']['prev_empl'] = $result['prev_empl'];
            $_SESSION['user']['inputs']['prev_empl'] = $result['prev_empl_vals'];

            header('location: '.$_SERVER['HTTP_REFERER']);
        }
        elseif(is_bool($result) && $result === TRUE){
            header('location: '.UNSECURE_URL.'/employee/created');

        }
    }

    public function edit() {
        // show the edit form
        echo 'edit action';
    }

    public function update() {
        // update the record
        echo 'update action';
    }

    public function destroy() {
        // delete a record
        echo 'destroy action';
    }

    /*
     * Create Employee account
     *
     * @return: bool
     * @$employeeDetails - array of inserted values in format 'column' => 'value'
     */
    public function createAccount($employeeDetails){
        list($employeeDetails, $prevEmplVals) = $this->getPrevEmplVals($employeeDetails);
        //die(var_dump($prevEmplVals));
        // check current employment values
        $validationResult['current_empl'] = $this->validateInputData($employeeDetails, 'current_empl');
        $validationResult['prev_empl'] = NULL;

        //check previous employment values
        if (!empty($prevEmplVals)){
            foreach ($prevEmplVals as $index => $values) {
                $prevEmplValsValidationResult = $this->validateInputData($values, 'prev_empl');
                if (!empty($prevEmplValsValidationResult)){
                    $validationResult['prev_empl'][$index] = $prevEmplValsValidationResult;
                }
            }
        }

        if (empty($validationResult['current_empl']) AND empty($validationResult['prev_empl'])){
            $employment = new Employment_Model();
            $officeAddress = new Address_Model();

            list($employeeValues, $officeAddressValues, $employmentValues) = $this->createInsertArrays($employeeDetails);

            $this->queryHandler->startTransaction();

            $employmentValues[$employment::EMPLOYEE] = $newEmployeeId = $this->model->createRecord($employeeValues, array('check_parent' => FALSE));
            $employmentValues[$employment::OFFICE_ADDRESS] = $officeAddress->createRecord($officeAddressValues);
            $employmentValues[$employment::CURRENT] = 1;
            $employment->createRecord($employmentValues);

            // insert previous employment records
            if (!empty($prevEmplVals)){
                foreach ($prevEmplVals as $values) {
                    list($employeeValues, $officeAddressValues, $employmentValues) = $this->createInsertArrays($values);
                    $employmentValues[$employment::OFFICE_ADDRESS] = $officeAddress->createRecord($officeAddressValues);
                    $employmentValues[$employment::EMPLOYEE] = $newEmployeeId;
                    $employment->createRecord($employmentValues);
                }
            }

            $this->queryHandler->commit();
            return true;
        }
        else{
            $validationResult['prev_empl_vals'] = $prevEmplVals;
            return $validationResult;
        }

    }

    private function validateInputData($employeeDetails, $dataType){
        $validation = new InputValidator();
        if ($dataType == 'current_empl') return $validation->validateEmployee($employeeDetails);
        if ($dataType == 'prev_empl') return $validation->validateEmployeePrevEmpl($employeeDetails);
    }

    /*
     * This function extracts array values from employee regist. form (previous employment)
     * and place them to separate array. Array values will be removed from original $_POST
     *
     */

    private function getPrevEmplVals($employeeDetails){
        $prevEmplVals = array();
        foreach ($employeeDetails as $key => $values) {
            if (strstr($key, PREV_PREFIX)){
                foreach ($values as $index => $value) {
                    $prevEmplVals[$index][$key] = $value;
                }
                unset($employeeDetails[$key]);
            }
        }
        return array($employeeDetails, $prevEmplVals);
    }

    private function createInsertArrays($employeeDetails){
        $employmentValues = $officeAddressValues = $employeeValues = array();
        foreach ($employeeDetails as $key => $value) {
            list($table, $column) = explode(SEP, $key);
            $table = str_replace(PREV_PREFIX, '', $table);
            switch($table){
                case Employee_Model::TABLE:
                    $employeeValues[$column] = $value;
                    if ($column == Employee_Model::PARENT AND $value == 0) continue;
                    if ($column == Employee_Model::DOB) $employeeValues[$column] = $this->getSQLDOB($value);
                    $employeeValues[Employee_Model::ADID] = 2222;//$_SESSION['user']['userADId'];
                    break;
                case Address_Model::TABLE: $officeAddressValues[$column] = $value;
                    break;
                case Employment_Model::TABLE:
                    $employmentValues[$column] = $value;
                    break;
            }
        }
        return array($employeeValues, $officeAddressValues, $employmentValues);
    }

    private function getSQLDOB($userDOB){
        $dob = explode(DOB_SEP, $userDOB);
        return date('Y-m-d', mktime(0,0,0,$dob[1],$dob[0],$dob[2]));
    }



}
