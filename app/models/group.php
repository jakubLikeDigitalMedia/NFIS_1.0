<?php

class Group_Model extends Model_Abstract{

    const TABLE = 'group';
    const PRM_KEY = 'grp_id';

    const TITLE = 'title';
    const DESC = 'description';
    const CREATED = 'created';


    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }



} 