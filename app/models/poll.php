<?php

class Poll_Model extends Model_Abstract{

    const TABLE = 'poll';
    const PRM_KEY = 'poll_id';

    const QUESTION = 'question';
    const CREATED = 'created';
    const ACTIVE = 'active';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 