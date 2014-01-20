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

    public function __construct($controller){

        $this->indexAction = $controller . '/index';
        $this->addAction = $controller . '/add';
        $this->createAction = $controller . '/create';
        $this->editAction = $controller . '/edit';
        $this->updateAction = $controller . '/update';
        $this->destroyAction = $controller . '/destroy';
    }


    public function getLink($action, $secure = FALSE){
        $action = $action.'Action';
        if (property_exists($this, $action)){
            return ($secure)? SECURE_URL.'/'.$this->{$action}: UNSECURE_URL.'/'.$this->{$action};
        }
        else return NULL;
    }

} 