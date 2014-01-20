<?php

class Brand_Media_Model extends Model_Abstract{

    const TABLE = 'brand_media';
    const PRM_KEY = 'brandmdia_id';

    const IMAGE = 'id_img';
    const VIDEO = 'id_vid';
    const BRAND = 'id_brand';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 