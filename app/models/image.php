<?php

class Image_Model extends Model_Abstract{

    const TABLE = 'image';
    const PRM_KEY = 'img_id';

    const TITLE = 'title';
    const DESC = 'description';
    const NAME = 'name';
    const UPLOADED = 'uploaded_by';
    const UPLOAD_DATE = 'upload_date';
    const ORG_NAME = 'original_name';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 