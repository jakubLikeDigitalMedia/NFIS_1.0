<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 17/12/2013
 * Time: 11:49
 */

class Department_Model extends Model_Abstract{

    const TABLE = 'department';
    const PRM_KEY = 'dprmt_id';

    const TITLE = 'title';

    public function __construct(){
        parent::init('dprmt_id', 'department');
    }

} 