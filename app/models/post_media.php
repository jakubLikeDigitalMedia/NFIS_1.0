<?php

class Post_Media_Model extends Model_Abstract{

    const TABLE = 'post_media';
    const PRM_KEY = 'postmdia_id';

    const VIDEO = 'id_vid';
    const IMAGE = 'id_img';
    const POST = 'id_post';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 