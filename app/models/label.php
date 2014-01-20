<?php

class Label_Model extends Model_Abstract{

    const TABLE = 'label';
    const PRM_KEY = 'label_id';

    const POST = 'id_post';
    const TAG = 'id_tag';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 