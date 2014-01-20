<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 17/12/2013
 * Time: 11:49
 */

class Position_Model extends Model_Abstract{

    const TABLE = 'position';
    const PRM_KEY = 'poss_id';

    const TITLE = 'title';
    const ORDER = 'order';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

} 