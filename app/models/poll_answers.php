<?php

class Poll_Answers_Model extends Model_Abstract{

    const TABLE = 'poll_answers';
    const PRM_KEY = 'pollanswr_id';

    const POLL = 'id_poll';
    const ANSWER = 'answer';
    const CORRECT = 'correct';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 