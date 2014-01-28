<?php

class Permissions_Model extends Model_Abstract{

    const TABLE = 'permissions';
    const PRM_KEY = 'perm_id';

    const GROUP = 'id_grp';
    const PAGE = 'id_page';

    const DISPLAY_POST = 'display_post';
    const ADD_POST = 'add_post';
    const ADD_COMMENT = 'add_comment';
    const ADD_VOTE = 'add_vote';

    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

    public function createInsertArrays($values, $groupId, $sitemap){
        $selectedPages = (empty($values['pages']))? array(): $values['pages'];
        $insertArrays = array();
        foreach ($sitemap as $section ) {
            foreach ($section['pages'] as $pageId => $page) {

                $insertArrays[] = array(
                    self::GROUP => $groupId,
                    self::PAGE => $pageId,
                    self::DISPLAY_POST => (in_array($pageId, $selectedPages))? 1: 0,
                    self::ADD_POST => $this->getCheckboxValue($values, self::ADD_POST.'-'.$pageId),
                    self::ADD_COMMENT => $this->getCheckboxValue($values, self::ADD_COMMENT.'-'.$pageId),
                    self::ADD_VOTE => $this->getCheckboxValue($values, self::ADD_VOTE.'-'.$pageId),
                );
            }

        }
        //die(var_dump($insertArrays));
        return $insertArrays;
    }

    public function update($_post){
        $group = new Group_Model();
        $groupId = $_post[$group::PRM_KEY];
        $groupTitle = $_post[$group::TITLE];
        $query = 'UPDATE `'.$group::TABLE.'` SET '.$group->getSqlQueryField($group::TITLE).' = \''.$groupTitle.'\' WHERE '.$group->getSqlQueryField($group::PRM_KEY).' = '.$groupId.';';
        $pagesToUpdate = $_post['pages'];
        foreach($pagesToUpdate as $pageId){
            $values = array(
                self::DISPLAY_POST => 1,
                self::ADD_POST => isset($_post[self::ADD_POST.'-'.$pageId])? 1: 0,
                self::ADD_COMMENT => isset($_post[self::ADD_COMMENT.'-'.$pageId])? 1: 0,
                self::ADD_VOTE => isset($_post[self::ADD_VOTE.'-'.$pageId])? 1: 0,
            );
            list($columns, $values) = $this->queryHandler->escapevalues($values);

            for ($i = 0; $i <= count($columns)-1; $i++) {
                $values[$i] = $columns[$i].' = '.$values[$i];
            }

            $query .= 'UPDATE '.self::TABLE.' SET '.implode(',',$values).' WHERE '.$this->getSqlQueryField(self::GROUP).' = '.$groupId.' AND '.$this->getSqlQueryField(self::PAGE).' = '.$pageId.';';

        }
        //die($query);

        $this->queryHandler->startTransaction();
        $this->queryHandler->multiQuery($query);
        $this->queryHandler->commit();

    }
} 