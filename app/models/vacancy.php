<?php

class Vacancy_Model extends Model_Abstract{

    const TABLE = 'vacancy';
    const PRM_KEY = 'vacancy_id';

    const BRAND = 'id_brand';

    const TITLE = 'title';
    const DESC = 'description';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 