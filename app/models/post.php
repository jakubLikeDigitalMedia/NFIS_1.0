<?php

class Post_Model extends Model_Abstract{

    const TABLE = 'post';
    const PRM_KEY = 'post_id';

    const EMPLOYEE = 'id_empl';
    const FORUM = 'id_forum';
    const POST_TYPE = 'id_posttp';

    const TITLE = 'title';
    const DESC = 'description';
    const CONTENT = 'content';
    const CREATED = 'created';
    const DISPLAYED_TIMES = 'displayed_times';
    const HIDDEN = 'hidden';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 