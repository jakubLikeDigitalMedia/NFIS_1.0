<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 17/12/2013
 * Time: 11:49
 */

class Brand_Model extends Model_Abstract{

    const TABLE = 'brand';
    const PRM_KEY = 'brand_id';

    const TITLE = 'title';
    const ORDER = 'order';
    const CONTENT = 'content';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

} 