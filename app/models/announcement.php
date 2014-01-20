<?php

class Announcement_Model extends Model_Abstract{

    const TABLE = 'announcement';
    const PRM_KEY = 'anncmt_id';

    const BRAND = 'id_brand';

    const TITLE = 'title';
    const CONTENT = 'content';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 