<?php

class Forum_Model extends Model_Abstract{

    const TABLE = 'forum';
    const PRM_KEY = 'forum_id';

    const EMPLOYEE = 'id_empl';

    const TITLE = 'title';
    const DESC = 'description';
    const CREATED = 'created';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 