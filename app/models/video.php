<?php

class Video_Model extends Model_Abstract{

    const TABLE = 'video';
    const PRM_KEY = 'vid_id';

    const TITLE = 'title';
    const DESC = 'description';
    const UPLOADED = 'uploaded_by';
    const UPLOAD_DATE = 'upload_date';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 