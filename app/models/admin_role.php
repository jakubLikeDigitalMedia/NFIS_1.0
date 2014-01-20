<?php

class Admin_Role_Model extends Model_Abstract{

    const TABLE = 'admin_role';
    const PRM_KEY = 'role_id';

    const TITLE = 'title';
    const DESC = 'description';
    const CREATED = 'created';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 