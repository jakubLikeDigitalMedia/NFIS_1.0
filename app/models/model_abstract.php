<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 17/12/2013
 * Time: 11:42
 */

abstract class Model_Abstract {

    // DB table info
    protected $primaryKey;
    protected $DbTable;

    protected $modelName;
    protected $modelFileName;

    protected $queryHandler;
    
    protected $formsDir;
    protected $formPath;

    public $actionLink;



    protected function init($primaryKey, $dbTable){

        $this->primaryKey = $primaryKey;
        $this->DbTable = $dbTable;

        $this->queryHandler = new QueryHandler();

        $this->modelName = str_replace('_Model', '', get_class($this));
        $this->modelFileName = strtolower($this->modelName);

        $this->formsDir = FORMS.'/'.$this->modelFileName;
        $this->formPath = $this->formsDir.'/'.$this->modelFileName.'.php';

        $this->actionLink = new ActionLink($this->modelFileName);


    }

    public function loadFormDefault($vars = NULL){
        if ($vars){
            foreach ($vars as $name => $value) {
                $$name = $value;
            }
        }

        if (file_exists($this->formPath)){
            include_once $this->formPath;
        }
        else echo 'Form not found in: '.$this->formPath;
    }

    public function loadForm($name, $vars = NULL){
        if ($vars){
            foreach ($vars as $name => $value) {
                $$name = $value;
            }
        }
        $formPath =  $this->formsDir.'/'.$name.'.php';
        if (file_exists($formPath)){
            include_once $formPath;
        }
        else echo 'Form not found in: '.$formPath;

    }

    public function getSqlQueryField($fieldName){
        return '`'. $this->DbTable . '`.`'. $fieldName .'`';
    }

    public function getTableCollumnName($fieldName){
        return $this->DbTable.SEP.$fieldName;
    }

    public function getPropertyList($propertyName, $orderType = 'DESC'){
        $query = "SELECT {$this->primaryKey}, $propertyName FROM {$this->DbTable} ORDER BY $propertyName $orderType";
        return $this->queryHandler->selectQuery($query, $this->primaryKey, 'list');

    }

    public function createRecord($insertValues, $options = NULL){
        //$queryHandler = new DbQueryManager();
        return $this->queryHandler->insert($insertValues, "`$this->DbTable`", $options);

    }

    public function getRecords($mysqlQuery, $type = 'array'){
        return $this->queryHandler->selectQuery($mysqlQuery, $this->primaryKey, $type);

    }

    public function getProperty($propertyName){
        if (property_exists($this, $propertyName)) return $this->{$propertyName};
        else return NULL;
    }

    public function getProperties($propertyNames){
        if (!is_array($propertyNames)) return NULL;
        else{
            $propertyValues = array();
            foreach ($propertyNames as $propertyName) {
                $propertyValues[$propertyName] = $this->getProperty($this, $propertyName);
            }

        }
    }

    public function getCheckboxValue($_post, $field_name){
        if(isset($_post[$field_name]) && ($_post[$field_name] === "1")){
            return $_post[$field_name];
        }else{
            return 0;
        }
    }

    public function recordExist($property, $value){
        $query =  "SELECT `{$this->primaryKey}`, $property FROM `{$this->DbTable}` WHERE `$property` = '$value'";
        $result = $this->queryHandler->selectQuery($query, $this->primaryKey, 'list');
        return (boolean)sizeof($result) > 0;
    }

    /*
     * ========================================================================================
     * Session handling when working with forms
     */

    public function saveInputsToSession($inputs){
        $_SESSION[$this->modelFileName]['inputs'] = $inputs;
    }

    public function getInputsFromSession(){
        return (isset($_SESSION[$this->modelFileName]['inputs']))? $_SESSION[$this->modelFileName]['inputs']: array();
    }

    public function saveErrorsToSession($errors){
        $_SESSION[$this->modelFileName]['errors'] = $errors;
    }

    public function getErrorsFromSession(){
        return (isset($_SESSION[$this->modelFileName]['errors']))? $_SESSION[$this->modelFileName]['errors']: array();
    }

    public function unsetSession(){
        if (isset($_SESSION[$this->modelFileName])) unset($_SESSION[$this->modelFileName]);
    }

    //==========================================================================================

    public function updateMultipleValues($values, $column){
        $dbc = new DbQueryManager();
        foreach($values as $value){
            $column_values[$column . '-' . $value] = $value;
        }
        $dbc->update($column_values, '', "`$this->DbTable`", array('multiple_update_one' => TRUE));
    }



} 