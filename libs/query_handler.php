<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 16/12/2013
 * Time: 14:00
 */

class QueryHandler {

    private $conn;


    public function __construct(){
        $this->conn = DBManager::getConnection();
    }

    public function selectQuery($query, $primaryKey, $returnType = 'array'){
        //die($query);
        $res = $this->conn->query($query);
        if ($res){

            if ($res->num_rows === 0) return NULL;

            $result = array();
            switch ($returnType){
                case 'array':
                    while($rec = $res->fetch_assoc()){
                        $id = $rec[$primaryKey];
                        unset($rec[$primaryKey]);
                        $result[$id] = $rec;
                    }
                    break;
                case 'object':
                    while($rec = $res->fetch_object()){
                        $result[$rec->{$primaryKey}] = $rec;
                    }
                    break;
                case 'list': // returns list as id => value array
                    while($rec = $res->fetch_assoc()){
                        $result[array_shift($rec)] = array_shift($rec);
                    }
            }
            return $result;
        }
        else{
            throw new DatabaseErrorException($this->conn->error);
        }

    }

    private function escapeValues($valuesArray){
        //die(var_dump($valuesArray));
        $columns = array();
        $values = array();
        foreach($valuesArray as $column => $value){
            $columns[] = $column;
            $values[] = $this->conn->real_escape_string($value);
        }
        return array($columns, $values);
    }


    /*
     * function iserts data into database
     *
     * @insertValues: array of values column => value
     */
    public function insert($insertValues, $table, $options = NULL){
        //die(var_dump($options));
        $query = '';

        if (isset($options['multiple_insert']) && $options['multiple_insert'] === TRUE){
            $query = $this->createMultipleInsertQuery($insertValues, $table);
        }
        elseif (isset($options['check_parent']) && $options['check_parent'] === FALSE){
            $query = 'SET FOREIGN_KEY_CHECKS = 0; '.$query;
            if ($this->multiQuery($query))  return $this->conn->insert_id; // last inserted id
        }
        else{
            list($columns, $values) = $this->escapeValues($insertValues);
            $query = "INSERT INTO $table (".implode(',', $columns).") VALUES ('".implode("','", $values)."')";
        }
        
        //die(var_dump($query));
        //echo($query).'</br>';
        if ($this->conn->query($query)){
            return $this->conn->insert_id; // last inserted id
        }
        else{
            $this->rollBack();
            throw new DatabaseErrorException($this->conn->error, $query);
        }
    }
    
   public function update($updateValues, $conditions, $table, $options = NULL){
        $query = '';

        if (empty($options)){
             list($columns, $values) = $this->escapeValues($updateValues);
             $newValues = array();
             for($i = 0; $i < count($columns)-1; $i++){
                 $newValues[] = ' ' . $columns[$i] . '=' . $values[$i];
             }
             
            $query = "UPDATE $table SET (".implode(',', $newValues).") WHERE (".implode(',', $conditions).")";
        }

        if (isset($options['multiple_update_one']) && $options['multiple_update_one'] === TRUE){
            list($columns, $values) = $this->escapeValues($updateValues);
            $column = explode('-',$columns[0])[0];
            
            $query = "UPDATE $table SET id_grp='$column' WHERE empl_id IN (" . implode(',', $values) . ")";
        }
        
        if ($this->conn->query($query)){
          //  return $this->conn->insert_id; // last inserted id
        }
        else{
            $this->rollBack();
            throw new DatabaseErrorException($this->conn->error, $query);
        }
    }

    private function multiQuery($multiQuery){
        if ($this->conn->multi_query($multiQuery)){
            do {
                if (!$this->conn->next_result()) throw new DatabaseErrorException($this->conn->error, $multiQuery);
            } while ($this->conn->more_results());
            if ($error = $this->conn->error){
                $this->rollBack();
                throw new DatabaseErrorException($error, $multiQuery);
            }
            else return TRUE;
        }
    }


    public function createMultipleInsertQuery($groupArray, $table){
        $tmpValues = array();
        $columns = '';
        foreach($groupArray as $page){
            list($columns, $values) = $this->escapeValues($page);
            $tmpValues[] = '('.implode(',', array_values($values)).')';
            $columns = (empty($columns))? implode(',', $columns): $columns;
        }
        $query = "INSERT INTO $table (" . implode(',',$columns) . ") VALUES ";
        return $query . implode(', ', $tmpValues);
    }


    public function startTransaction(){
        $this->conn->autocommit(FALSE);
    }

    private function rollBack(){
        $this->conn->rollback();

    }

    public function commit(){
        $this->conn->commit();
    }




} 