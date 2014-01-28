<?php

class Group_Controller extends App_Controller{

    public function index(){
        echo 'index action';
    }

    public function any_function_here(){

    }

    public function add() {
        session_start();
        $page = new Page_Model();

        $inputErrors = $this->getModel()->getErrorsFromSession();
        $inputs = $this->getModel()->getInputsFromSession();
        $sitemap = $page->getSiteMap();
        $actionLink = $this->actionLink->getLink('create');

        $this->vars = array(
            'errors' => $inputErrors,
            'inputs' => $inputs,
            'sitemap' => $sitemap,
            'edit' => FALSE,
            'actionLink' => $actionLink
        );
    }

    public function create() {
        //die('create');
        session_start();

        $group = $this->getModel();
        $page = new Page_Model();
        $validator = new InputValidator();
        $result = $validator->validateGroup($_POST);
        if ($result){
            $group->saveInputsToSession($_POST);
            $group->saveErrorsToSession($result);
            header('Location: '.$_SERVER['HTTP_REFERER']);
            return;
        }
        $sitemap = $page->getSiteMap();
        $this->createGroup($_POST, $sitemap);
        header('Location: '.$this->actionLink->getLink('index'));
        return;
    }

    public function edit() {
        session_start();
        $page = new Page_Model();
        $this->getParams();
        $groupId = $this->getParam(1);
        if (empty($groupId)){
            $this->vars = array(
                'exist' => FALSE,
                'errorMessage' => "No group id selected"
            );
            return;
        }
        else{
            // get title
            $title = $this->getModel()->getRecords('SELECT '.$this->getModel()->getSqlQueryField(Group_Model::PRM_KEY).','.$this->getModel()->getSqlQueryField(Group_Model::TITLE).' FROM `'. Group_Model::TABLE. '` WHERE '. Group_Model::PRM_KEY.' = '.$groupId, 'list');
            if (empty($title)){
                $this->vars = array(
                    'exist' => FALSE,
                    'errorMessage' => "Group with id: $groupId doesn't exist"
                );
                return;
            }
            else{
                $title = array_values($title);
                $title = (isset($title[0]))? $title[0]: '';
                $_SESSION[Group_Model::TITLE] = $title;
                $initInputs = $page->getSiteMapWithPermissions($groupId);
                $sessionInputs = $this->getModel()->getInputsFromSession();

                $inputs = (!empty($sessionInputs))? $sessionInputs: array(Group_Model::TITLE => $title); // pass init value if form loaded first time
                $inputErrors = $this->getModel()->getErrorsFromSession();

                $this->vars = array(
                    'inputs' => $inputs,
                    'errors' => $inputErrors,
                    'sitemap' => $initInputs,
                    'edit' => TRUE,
                    'exist' => TRUE,
                    'groupId' => $groupId,
                    'actionLink' => $this->actionLink->getLink('update')
                );
            }
        }

    }

    public function update() {
        session_start();
        $group = $this->getModel();
        $page = new Page_Model();
        $validator = new InputValidator();
        $result = $validator->validateGroup($_POST, FALSE);
        if ($result){
            $group->saveInputsToSession($_POST);
            $group->saveErrorsToSession($result);
            header('Location: '.$_SERVER['HTTP_REFERER']);
            return;
        }
        $permissions = new Permissions_Model();
        $permissions->update($_POST);
        header('Location: '.$this->actionLink->getLink('index'));
        die();

    }

    public function destroy() {
        // delete a record
        echo 'destroy action';
    }

    public function createGroup($_post, $sitemap){
        //die('crete group');
        $group = $this->getModel();
        $groupName = $_post[$group::TITLE];

        $this->queryHandler->startTransaction();

        $groupId = $group->createRecord(array($group::TITLE => $groupName));
        //adding permissions for the group
        $permissions = new Permissions_Model();
        $insertArrays = $permissions->createInsertArrays($_post, $groupId, $sitemap);// creating a multiple insert

        $permissions->createRecord($insertArrays, array('multiple_insert' => TRUE));

        $this->queryHandler->commit();
        return TRUE;
    }



}
