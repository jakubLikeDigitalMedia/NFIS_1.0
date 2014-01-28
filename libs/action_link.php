<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 20/01/2014
 * Time: 15:42
 */

class ActionLink {

    protected $indexAction;
    protected $addAction;
    protected $createAction;
    protected $editAction;
    protected $updateAction;
    protected $destroyAction;
    protected $add_employee_to_groupAction;

    private $controllerName;

    public function __construct($controller){

        $this->indexAction = $controller . '/index';
        $this->addAction = $controller . '/add';
        $this->createAction = $controller . '/create';
        $this->editAction = $controller . '/edit';
        $this->updateAction = $controller . '/update';
        $this->destroyAction = $controller . '/destroy';
        
        $this->add_employee_to_groupAction = $controller . '/add_employee_to_group';

        $this->controllerName = $controller;
    }


    public function getLink($action, $secure = FALSE){
        $actionLink = $this->controllerName.'/'.$action;
        return ($secure)? SECURE_URL.'/'.$actionLink: UNSECURE_URL.'/'.$actionLink;

    }

} 