<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 19/12/2013
 * Time: 11:56
 */

class Employment_Model extends Model_Abstract{

    const TABLE = 'employment';
    const PRM_KEY = 'emplmt_id';
    const EMPLOYEE = 'id_empl';
    const POSITION = 'id_type';
    const DEPARTMENT = 'id_dprmt';
    const BRAND = 'id_brand';
    const OFFICE_ADDRESS = 'id_offaddr';
    const LOCATION = 'id_location';
    const CURRENT = 'current';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

} 