<?php

class Post_Type_Model extends Model_Abstract{

    const TABLE = 'post_type';
    const PRM_KEY = 'posttp_id';

    const TYPE = 'type';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 