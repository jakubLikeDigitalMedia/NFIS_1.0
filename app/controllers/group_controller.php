<?php

class Group_Controller extends App_Controller{

    public function index(){
        echo 'index action';
    }

    public function add() {
        session_start();
        $groupModel = $this->getModel();
        $page = new Page_Model();

        $inputErrors = $groupModel->getErrorsFromSession();
        $inputs = $groupModel->getInputsFromSession();
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
        // show the edit form
        echo 'edit action';
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
