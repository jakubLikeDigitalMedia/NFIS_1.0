<?php

class Comment_Votes_Model extends Model_Abstract{

    const TABLE = 'comment_votes';
    const PRM_KEY = 'vote_id';

    const EMPLOYEE = 'id_empl';
    const COMMENT = 'id_comm';

    const CREATED = 'created';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }
} 