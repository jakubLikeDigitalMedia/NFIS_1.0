<?php

class Section_Access_Model extends Model_Abstract{

    const TABLE = 'section_access';
    const PRM_KEY = 'sectnaccss_id';

    const ADMIN_ROLE = 'id_role';
    const ADMIN_SECTION = 'id_adminsctn';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 