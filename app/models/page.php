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
            LEFT JOIN ". $section::TABLE ." ON ". $this->getSqlQueryField(self::SECTION) ." = ". $section->getSqlQueryField($section::PRM_KEY) ."
            ORDER BY section_id";
        //die($query);
        $pageList = $this->getRecords($query, 'object');
        return $this->createSiteMap($pageList);

    }

    public function getSiteMapWithPermissions($groupId = NULL){

        $section = new Section_Model();
        $permissions = new Permissions_Model();
        $group = new Group_Model();

        $query = "SELECT
        ". $section->getSqlQueryField($section::TITLE) . " AS 'section_title',
        ". $section->getSqlQueryField($section::PRM_KEY) ." AS 'section_id',
        ". $section->getSqlQueryField($section::CODE) ." AS 'section_code',
        ". $this->getSqlQueryField(self::PRM_KEY) ." AS page_id,
        ". $this->getSqlQueryField(self::TITLE) ." AS page_title,
        ". $this->getSqlQueryField(self::CODE) ." AS page_code,
        ". $permissions->getSqlQueryField($permissions::GROUP) ." AS permissions_group_id,
        ". $permissions->getSqlQueryField($permissions::ADD_POST) ." AS permissions_add_post,
        ". $permissions->getSqlQueryField($permissions::ADD_COMMENT) ." AS permissions_add_comment,
        ". $permissions->getSqlQueryField($permissions::ADD_VOTE) ." AS permissions_add_vote
            FROM ". self::TABLE ."
            LEFT JOIN ". $section::TABLE ." ON ". $this->getSqlQueryField(self::SECTION) ." = ". $section->getSqlQueryField($section::PRM_KEY) ."
            LEFT JOIN ". $permissions::TABLE ." ON ". $permissions->getSqlQueryField($permissions::PAGE) ." = ". $this->getSqlQueryField(self::PRM_KEY);
        if ($groupId) $query .= "WHERE ".$group::PRM_KEY." = ".(is_numeric($groupId))? $groupId: 1;
        $query .= "ORDER BY section_id";

        //die($query);
        $pageList = $this->getRecords($query, 'object');
        //var_dump($resultArray);
        return $this->createSiteMap($pageList, TRUE);

    }

    private function createSiteMap($pageList, $permissions = FALSE){
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
                $pageArray[$pageId][Permissions_Model::GROUP] = $page->permissions_group_id;
                $pageArray[$pageId][Permissions_Model::ADD_POST] = $page->permissions_add_post;
                $pageArray[$pageId][Permissions_Model::ADD_VOTE] = $page->permissions_add_comment;
                $pageArray[$pageId][Permissions_Model::ADD_COMMENT] = $page->permissions_add_vote;
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