<?php

class Vacanc_Aply_Model extends Model_Abstract{

    const TABLE = 'vacancy_aply';
    const PRM_KEY = 'apply_id';

    const EMPLOYEE = 'id_empl';
    const VACANCY = 'id_vacancy';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 