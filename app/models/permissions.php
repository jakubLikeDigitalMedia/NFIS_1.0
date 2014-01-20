<?php

class Permissions_Model extends Model_Abstract{

    const TABLE = 'permissions';
    const PRM_KEY = 'perm_id';

    const GROUP = 'id_grp';
    const PAGE = 'id_page';

    const ADD_POST = 'add_post';
    const ADD_COMMENT = 'add_comment';
    const ADD_VOTE = 'add_vote';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

    public function createInsertArrays($values, $groupId){
        $selectedPages = $values['pages'];
        $insertArrays = array();
        foreach ($selectedPages as $pageId) {
            $insertArrays[] = array(
                self::GROUP => $groupId,
                self::PAGE => $pageId,
                self::ADD_POST => $this->getCheckboxValue($values, self::ADD_POST.'-'.$pageId),
                self::ADD_COMMENT => $this->getCheckboxValue($values, self::ADD_COMMENT.'-'.$pageId),
                self::ADD_VOTE => $this->getCheckboxValue($values, self::ADD_VOTE.'-'.$pageId),
            );
        }
        return $insertArrays;
    }
} 