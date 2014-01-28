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

    public function getEmployee_ModelsList($category = '', $filter_by = ''){
        if($filter_by == "Select_value"){
         $category = '';
    }
    $groupCls = new Group_Model();
    $brandCls = new Brand_Model();
    $positionCls = new Position_Model();
    $locationCls = new Location_Model();
    $departmentCls = new Department_Model();
    $employmentCls = new Employment_Model();
    $where = '';
//    $where = ' WHERE ';
//    switch($category){
//          case 'id_group':
//              if($filter_by == "all"){
//                  $where = '';
//              }else{
//                  $where .= $groupCls->getSqlQueryField($groupCls::TITLE) . "='$filter_by'";
//              }
//                break;
//          case 'id_brand':
//              $where .= $brandCls->getSqlQueryField($brandCls::TITLE) . "='$filter_by'";
//                break;
//          case 'id_type':
//              $where .= $positionCls->getSqlQueryField($positionCls::TITLE) . "='$filter_by'";
//                break;
//          case 'id_location':
//              $where .= $locationCls->getSqlQueryField($locationCls::TITLE) . "='$filter_by'";
//                break;
//          case 'id_dprmt':
//              $where .= $departmentCls->getSqlQueryField($departmentCls::TITLE) . "='$filter_by'";
//                break;
//          default:
//              $where .= $groupCls->getSqlQueryField($groupCls::TITLE) . "='default'";
//      }

            $query = "SELECT " . $this->getSqlQueryField($this::NAME) . " AS 'name',
            " . $this->getSqlQueryField($this::PRM_KEY) . " AS 'empl_id',
            " . $this->getSqlQueryField($this::SURNAME) . " AS 'surname',
            " . $this->getSqlQueryField($this::GROUP) . " AS 'group_id',
            " . $groupCls->getSqlQueryField($groupCls::TITLE) . " AS 'group',
            " . $brandCls->getSqlQueryField($brandCls::TITLE) . " AS 'brand',
            " . $positionCls->getSqlQueryField($positionCls::TITLE) . " AS 'position',
            " . $locationCls->getSqlQueryField($locationCls::TITLE) . " AS 'location',
            " . $departmentCls->getSqlQueryField($departmentCls::TITLE) . " AS 'department'
            FROM ". $employmentCls::TABLE ." LEFT JOIN ". $this::TABLE ." ON " . $this->getSqlQueryField($this::PRM_KEY) . " = " . $employmentCls->getSqlQueryField($employmentCls::EMPLOYEE) . "
             LEFT JOIN ". $brandCls::TABLE ." ON " . $brandCls->getSqlQueryField($brandCls::PRM_KEY) . " = " . $employmentCls->getSqlQueryField($employmentCls::BRAND) . "
             LEFT JOIN ". $positionCls::TABLE ." ON " . $positionCls->getSqlQueryField($positionCls::PRM_KEY) . " = " . $employmentCls->getSqlQueryField($employmentCls::POSITION) . "
             LEFT JOIN ". $locationCls::TABLE ." ON " . $locationCls->getSqlQueryField($locationCls::PRM_KEY) . " = " . $employmentCls->getSqlQueryField($employmentCls::LOCATION) . "
             LEFT JOIN ". $departmentCls::TABLE ." ON " . $departmentCls->getSqlQueryField($departmentCls::PRM_KEY) . " = " . $employmentCls->getSqlQueryField($employmentCls::DEPARTMENT) . "
             LEFT JOIN `". $groupCls::TABLE ."` ON " . $groupCls->getSqlQueryField($groupCls::PRM_KEY) . " = " . $this->getSqlQueryField($this::GROUP) . "
            " . $where;
            
        $dbc = new QueryHandler();
        $result = $dbc->selectQuery($query, $this::PRM_KEY);
        $resultCopy = $result;
        $index = 0;
        foreach($result as $id => $value){
            $resultCopy[$id]['add'] = "<input type='checkbox' name='group_employee[]' value='$id' data-order='$index'>";
            $resultCopy[$id]['index'] = $index;
            $index++;
        }
        return json_encode(array_values($resultCopy));
    }







}
