<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 16/12/2013
 * Time: 13:07
 */

class Page_Model extends Model_Abstract{

    const TABLE = 'page';
    const PRM_KEY = 'page_id';

    const SECTION = 'id_section';
    const TITLE = 'title';
    const CODE = 'code';


    public function __construct(){
        parent::init(self::PRM_KEY, self::TABLE);
    }

    public function renderNavigation(){
        $nav = 'Navigation';
        return $nav;
    }

    public function getSiteMap(){
        $section = new Section_Model();

        $query = "SELECT
        ". $section->getSqlQueryField($section::TITLE) . " AS 'section_title',
        ". $section->getSqlQueryField($section::PRM_KEY) ." AS 'section_id',
        ". $section->getSqlQueryField($section::CODE) ." AS 'section_code',
        ". $this->getSqlQueryField(self::PRM_KEY) ." AS page_id,
        ". $this->getSqlQueryField(self::TITLE) ." AS page_title,
        ". $this->getSqlQueryField(self::CODE) ." AS page_code
        FROM ". self::TABLE ."
        LEFT JOIN ". $section::TABLE ." ON ". $this->getSqlQueryField(self::SECTION) ." = ". $section->getSqlQueryField($section::PRM_KEY)."
        ORDER BY section_id";
        //die($query);
        $pageList = $this->getRecords($query, 'object');
        return $this->createSiteMap($pageList);

    }

    public function getSiteMapWithPermissions($groupId){
        //var_dump($groupId);

        $section = new Section_Model();
        $permissions = new Permissions_Model();
        $group = new Group_Model();

        $query = "SELECT
        ". $section->getSqlQueryField($section::PRM_KEY) ." AS 'section_id',
        ". $section->getSqlQueryField($section::CODE) ." AS 'section_code',
        ". $section->getSqlQueryField($section::TITLE) . " AS 'section_title',
        ". $this->getSqlQueryField(self::PRM_KEY) ." AS page_id,
        ". $this->getSqlQueryField(self::CODE) ." AS page_code,
        ". $this->getSqlQueryField(self::TITLE) ." AS page_title,
        ". $permissions->getSqlQueryField($permissions::GROUP) ." AS group_id,
        ". $permissions->getSqlQueryField($permissions::DISPLAY_POST) ." AS display_post,
        ". $permissions->getSqlQueryField($permissions::ADD_POST) ." AS add_post,
        ". $permissions->getSqlQueryField($permissions::ADD_COMMENT) ." AS add_comment,
        ". $permissions->getSqlQueryField($permissions::ADD_VOTE) ." AS add_vote,
        ". $group->getSqlQueryField($group::PRM_KEY)." AS group_id
            FROM ". self::TABLE ."
            LEFT JOIN ". $section::TABLE ." ON ". $this->getSqlQueryField(self::SECTION) ." = ". $section->getSqlQueryField($section::PRM_KEY) ."
            LEFT JOIN ". $permissions::TABLE ." ON ". $permissions->getSqlQueryField($permissions::PAGE) ." = ". $this->getSqlQueryField(self::PRM_KEY)."
            LEFT JOIN `". $group::TABLE . "` ON ". $group->getSqlQueryField($group::PRM_KEY). " = ".$permissions->getSqlQueryField($permissions::GROUP);
            $groupId = (is_numeric($groupId))? $groupId: 1;
            $query .= " WHERE ".$group->getSqlQueryField($group::PRM_KEY)." = ".$groupId;
            $query .= " ORDER BY `section_id`";

        //die($query);
        $pageList = $this->getRecords($query, 'object');
        //die(var_dump($pageList));
        return $this->createSiteMap($pageList, TRUE);

    }

    private function createSiteMap($pageList, $permissions = FALSE){
        //if (empty($pageList)) return NULL;
        $pageArray = array();
        $sectionArray = array();
        $previousSectionId = NULL;
        $previousPage_Model = NULL;
        $i = 1;
        foreach($pageList as $pageId => $page){

            $currentSectionId = $page->section_id;
            if (($currentSectionId != $previousSectionId) AND !empty($previousSectionId)){
                $sectionArray[$previousPage_Model->section_id] = array(
                    Section_Model::TITLE => $previousPage_Model->section_title,
                    Section_Model::CODE => $previousPage_Model->section_code,
                    "pages" => $pageArray
                );
                $pageArray = array();
            }

            $pageArray[$pageId][self::TITLE] = $page->page_title;
            $pageArray[$pageId][self::CODE] = $page->page_code;

            if ($permissions){
                $pageArray[$pageId][Permissions_Model::GROUP] = $page->group_id;
                $pageArray[$pageId][Permissions_Model::DISPLAY_POST] = $page->display_post;
                $pageArray[$pageId][Permissions_Model::ADD_POST] = $page->add_post;
                $pageArray[$pageId][Permissions_Model::ADD_COMMENT] = $page->add_comment;
                $pageArray[$pageId][Permissions_Model::ADD_VOTE] = $page->add_vote;

            }

            if( count($pageList) == $i){
                $sectionArray[$page->section_id] = array(
                    Section_Model::TITLE => $page->section_title,
                    Section_Model::CODE => $page->section_code,
                    "pages" => $pageArray
                );
                $pageArray = array();
            }

            $previousSectionId = $page->section_id;
            $previousPage_Model = $page;
            $i++;
        }
        return $sectionArray;
    }

} 