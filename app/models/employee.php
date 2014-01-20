<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 13/12/2013
 * Time: 13:00
 */

class Employee_Model extends Model_Abstract{

    const TABLE = 'employee';
    const PRM_KEY = 'empl_id';
    const ADID = 'AD_id';

    const GROUP = 'id_grp';
    const ADMIN_ROLE = 'id_admin_role';
    const PARENT = 'parent_id';

    const NAME = 'name';
    const SURNAME = 'surname';
    const DOB = 'date_of_birth';
    const EMAIL = 'email';
    const PHONE_NUMBER = 'phone_number';


    const CREATED = 'created';
    const ADMIN_VRFD = 'verified_by_admin';
    const DOC_PERM = 'upload_doc_perm';

    const SEP = '.';

    protected $activeDirId = NULL;
    protected $name = NULL;
    protected $surname = NULL;
    protected $email = NULL;
    protected $positionId = NULL;
    protected $departmentId = NULL;
    protected $brandId = NULL;
    protected $locationId = NULL;

    //intranet permission
    protected $createForum = NULL;
    protected $uploadDoc = NULL;
    protected $adminPerm = NULL;

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

    /*
     * Getter Methods
     * --------------
     */

    public function getEmployee_Models($from, $to){
        // return list of employees objects from db
    }

    public static function getParentId($name){
        /*
        $queryHandler = new DbQueryManager();
        $query =  'SELECT '.E_SUPERIOR.' FROM '.E_TABLE. 'WHERE '.E_NAME." LIKE %$name%";
        return $queryHandler->selectQuery($query,E_PRM_KEY);
        */
        return NULL;
    }

    public function getSuperiors(){
        return NULL;
    }

    /*
     * -----------------------------------------------
     */


    /*
     * Loads employee info from DB
     * used in __construct
     */
    public function loadEmployee_Model($AD_id){
        $query = "SELECT `AD_id` FROM `{$this->DbTable}` WHERE `AD_id` = $AD_id";
        $employee = $this->queryHandler->selectQuery($query, $this->primaryKey);
        if (empty($employee)) return NULL;
        elseif (count($employee) > 1){
            throw new DuplicitDBValuesException($this->DBtable, 'AD_id');
        }
        else{
            $this->activeDirId = $AD_id;
        }


    }

    public function getEmployee_ModelsList($new_group = ''){
        $query = "SELECT `". E_TABLE . "`.`". E_NAME ."` AS 'name',
            `". E_TABLE ."`.`". E_PRM_KEY ."` AS 'empl_id',
            `". E_TABLE ."`.`". E_SURNAME ."` AS 'surname',
            `". E_TABLE ."`.`". E_GROUP ."` AS 'group_id',
            `". G_TABLE ."`.`". G_TITLE ."` AS 'group',
            `". B_TABLE ."`.`". B_TITLE ."` AS 'brand',
            `". P_TABLE ."`.`". P_TITLE ."` AS 'position',
            `". L_TABLE ."`.`". L_TITLE ."` AS 'location',
            `". DE_TABLE ."`.`". DE_TITLE ."` AS 'department'
            FROM ". EMPL_TABLE ." LEFT JOIN ". E_TABLE ." ON `". E_TABLE ."`.`". E_PRM_KEY ."` = `". EMPL_TABLE ."`.`". EMPL_EMPLOYEE . "`
             LEFT JOIN ". B_TABLE ." ON `". B_TABLE ."`.`". B_PRM_KEY ."` = `". EMPL_TABLE ."`.`". EMPL_BRAND . "`
             LEFT JOIN ". P_TABLE ." ON `". P_TABLE ."`.`". P_PRM_KEY ."` = `". EMPL_TABLE ."`.`". EMPL_POSITION . "`
             LEFT JOIN ". L_TABLE ." ON `". L_TABLE ."`.`". L_PRM_KEY ."` = `". EMPL_TABLE ."`.`". EMPL_LOCATION . "`
             LEFT JOIN ". DE_TABLE ." ON `". DE_TABLE ."`.`". DE_PRM_KEY ."` = `". EMPL_TABLE ."`.`". EMPL_DEPARTMENT . "`
             LEFT JOIN `". G_TABLE ."` ON `". G_TABLE ."`.`". G_PRM_KEY ."` = `". E_TABLE ."`.`". E_GROUP . "`
            " . ((!empty($new_group))?" WHERE `" . E_TABLE."`.`".E_GROUP ."`='$new_group'":" WHERE `" . G_TABLE."`.`".G_TITLE ."`='default'");

        $dbc = new DbQueryManager();
        $result = $dbc->selectQuery($query, E_PRM_KEY);
        $resultCopy = $result;
        foreach($result as $id => $value){
            $resultCopy[$id]['add'] = "<input type='checkbox' name='group_employee[]' value='$id'>";
        }
        return json_encode(array_values($resultCopy));
    }







}
