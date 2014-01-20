<?php

class Tag_Model extends Model_Abstract{

    const TABLE = 'tag';
    const PRM_KEY = 'tag_id';

    const TITLE = 'title';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 