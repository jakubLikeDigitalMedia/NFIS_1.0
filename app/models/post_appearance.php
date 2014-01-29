<?php

class Post_Appearance_Model extends Model_Abstract{

    const TABLE = 'post_appearance';
    const PRM_KEY = 'postaprnc_id';

    const DISPLAY_PAGE = 'display_page_id';
    const PAGE = 'id_page';

    const DEF = 'default';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

    public function getPostAppearances(){
        $query =
            'SELECT '.
            $this->getSqlQueryField(self::PRM_KEY).','.
            $this->getSqlQueryField(self::PAGE).','.
            $this->getSqlQueryField(self::DISPLAY_PAGE).
            'FROM `'.$this->DbTable.'` WHERE '.$this->getSqlQueryField(self::PAGE).' != '.$this->getSqlQueryField(self::DISPLAY_PAGE);

        return $this->getRecords($query);
    }

    private function createInsertArrays($_post){
        //var_dump($_post);
        $insertArrays = array();
        foreach ($_post['default'] as $pageId) {
            $insertArrays[] = array( self::PAGE => $pageId, self::DISPLAY_PAGE => $pageId );
        }

        foreach ($_post[self::DISPLAY_PAGE] as $assignment) {
            list($pageId, $displayPageId) = explode('-', $assignment);
            $insertArrays[] = array( self::PAGE => $pageId, self::DISPLAY_PAGE => $displayPageId );
        }
        return $insertArrays;
    }

    public function deleteDisplayRules(){
        $query = 'DELETE FROM `'.$this->DbTable.'`';
        return $this->deleteRecord($query);
    }

    public function createDisplayRules($_post){
        $this->createRecord($this->createInsertArrays($_post), array('multiple_insert' => TRUE));
    }
} 