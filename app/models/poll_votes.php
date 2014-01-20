<?php

class Poll_Votes_Model extends Model_Abstract{

    const TABLE = 'poll_votes';
    const PRM_KEY = 'pollvote_id';

    const EMPLOYEE = 'id_empl';
    const POLL = 'id_poll';

    const ANSWER = 'id_answer';
    const DATE = 'date';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 