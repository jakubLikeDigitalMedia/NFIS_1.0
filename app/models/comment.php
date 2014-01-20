<?php

class Comment_Model extends Model_Abstract{

    const TABLE = 'comment';
    const PRM_KEY = 'comm_id';

    const EMPLOYEE = 'id_empl';
    const POST = 'id_post';

    const CONTENT = 'content';
    const CREATED = 'created';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 