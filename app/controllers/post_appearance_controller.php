<?php

class Post_Appearance_Controller extends App_Controller{

    public function index(){
        echo 'index action';
    }

    public function add() {

    }

    public function create() {
        $postAppearanceModel = $this->getModel();
        $postAppearanceModel->createDisplayRules($_POST);
        header('location: '. $this->actionLink->getLink('set'));
    }

    public function edit() {

    }

    public function update() {
        // delete all records where id_page is in post
        $this->getModel()->deleteDisplayRules();

        // insert new values
        $this->create();
    }

    public function destroy() {
        // delete a record
        echo 'destroy action';
    }

    public function set(){

        $postAppearances = $this->getModel()->getPostAppearances();
        $actionLink = (!empty($postAppearances))? $this->actionLink->getLink('update'): $this->actionLink->getLink('create');
        $pageModel = new Page_Model();
        $siteMap = $pageModel->getSiteMap();
        $inputErrors = $this->getModel()->getErrorsFromSession();
        $inputs = $this->getModel()->getInputsFromSession();
        $pageList  = $pageModel->queryHandler->selectQuery(
            'SELECT '.$pageModel->getSqlQueryField($pageModel::PRM_KEY).','.$pageModel->getSqlQueryField($pageModel::TITLE).
            'FROM `'.$pageModel::TABLE.'`
            WHERE '.$pageModel->getSqlQueryField($pageModel::SECTION).' = 1', $pageModel::PRM_KEY, 'list'
        );

        $this->vars = array(
            'sitemap' => $siteMap,
            'inputs' => $inputs,
            'errors' => $inputErrors,
            'actionLink' => $actionLink,
            'pageList' => $pageList,
            'postAppearances' => $postAppearances,

        );

    }



}
