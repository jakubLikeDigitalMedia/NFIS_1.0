<?php

class Group_Controller extends App_Controller{

    public function index(){
        echo 'index action';
    }

    public function add() {
        session_start();
        $page = new Page_Model();

        $inputErrors = $this->getModel()->getErrorsFromSession();
        $inputs = $this->getModel()->getInputsFromSession();
        $sitemap = $page->getSiteMap();
        $actionLink = $this->actionLink->getLink('create');

        $this->vars = array(
            'inputErrors' => $inputErrors,
            'inputs' => $inputs,
            'sitemap' => $sitemap,
            'actionLink' => $actionLink
        );
    }

    public function create() {
        session_start();

        $group = $this->getModel();
        $result = $this->createGroup($_POST);
        //die(var_dump($result));
        if(is_string($result)){
            $group->saveInputsToSession($_POST);
            $group->saveErrorsToSession(array($group::TITLE => $result));
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }else{
            header('Location: '.$this->actionLink->getLink('index'));
        }
    }

    public function edit() {
        session_start();
        $page = new Page_Model();
        // get params from url;
        $params = $this->getParams();
        $groupId = (isset($params[0]))? $params[0]: NULL;
        // get title
        $title = $this->getModel()->getRecords('SELECT '.$this->getModel()->getSqlQueryField(Group_Model::TITLE).' FROM `'. Group_Model::TABLE. '` WHERE '. Group_Model::PRM_KEY.' = '.$groupId, 'list');
        $title = (isset($title[0]))? $title[0]: '';
        $initInputs = $page->getSiteMapWithPermissions($groupId);
        $sessionInputs = $this->getModel()->getInputsFromSession();
        $inputs = (isset($sessionInputs))? $sessionInputs: $initInputs;
        //$inputs[Group_Model::TITLE] = $title;
        $inputErrors = $this->getModel()->getErrorsFromSession();
        $this->vars = array(
            'inputs' => $inputs,
            'errors' => $inputErrors,
            'sitemap' => $initInputs,
            'actionLink' => $this->actionLink->getLink('update')
        );

    }

    public function update() {
        // update the record
        echo 'update action';
    }

    public function destroy() {
        // delete a record
        echo 'destroy action';
    }

    public function createGroup($_post){
        $group = $this->getModel();
        $groupName = $_post[$group::TITLE];
        // validation;

        if ($group->recordExist($group::TITLE, $groupName)) return 'Group with name '.$_post[$group::TITLE].' already exists';
        else{
            $this->queryHandler->startTransaction();

            $groupID = $group->createRecord(array($group::TITLE => $groupName));
            //adding permissions for the group
            $permissions = new Permissions_Model();
            $insertArrays = $permissions->createInsertArrays($_post, $groupID);// creating a multiple insert
            $permissions->createRecord($insertArrays, array('multiple_insert' => TRUE));

            $this->queryHandler->commit();
            return TRUE;
        }
    }

}
