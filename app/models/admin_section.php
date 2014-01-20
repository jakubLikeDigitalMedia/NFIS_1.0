<?php

class Admin_Section_Model extends Model_Abstract{

    const TABLE = 'admin_section';
    const PRM_KEY = 'adminsctn_id';

    const PARENT = 'parent_id';

    const TITLE = 'title';
    const DESC = 'description';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 