<?php

class Document_Model extends Model_Abstract{

    const TABLE = 'document';
    const PRM_KEY = 'doc_id';

    const EMPLOYEE = 'id_empl';

    const TITLE = 'title';
    const DESC = 'descr';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 