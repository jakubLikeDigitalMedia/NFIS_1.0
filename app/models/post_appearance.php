<?php

class Post_Appearance_Model extends Model_Abstract{

    const TABLE = 'post_appearance';
    const PRM_KEY = 'postaprnc_id';

    const POST_TYPE = 'id_posttp';
    const PAGE = 'id_page';

    const DEF = 'default';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 