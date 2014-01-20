<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 17/12/2013
 * Time: 11:49
 */

class Location_Model extends Model_Abstract{

    const TABLE = 'location';
    const PRM_KEY = 'location_id';

    const TITLE = 'title';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

} 