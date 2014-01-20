<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 19/12/2013
 * Time: 10:47
 */

class Address_Model extends Model_Abstract{

    const TABLE = 'office_address';
    const PRM_KEY = 'id_offaddr';

    const STREET = 'house_num_street';
    const POSTCODE = 'postcode';
    const CITY = 'city';
    const COUNTY = 'county';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
}
