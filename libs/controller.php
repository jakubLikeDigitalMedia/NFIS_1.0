<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 18/01/14
 * Time: 16:55
 */



class Controller {

    protected $viewVars = array();
    protected $model;

    protected  $controllerName;
    protected  $controllerFileName;

    public $actionLink;

    public function __construct(){
        $this->controllerName = str_replace('_Controller', '', get_class($this));
        $this->controllerFileName = strtolower($this->controllerName);

        $this->loadModel();

        $this->viewVars['controller'] = $this;
        $this->actionLink = new ActionLink($this->controllerFileName);
    }

    public function __set($varName, $value){
        $this->viewVars[$varName] = $value;
    }

    public function __get($varName){
        return (isset($this->viewVars[$varName]))? $this->viewVars[$varName]: NULL;
    }

    private function loadModel(){
        $modelClassName = $this->controllerName.'_Model';
        //die($modelClassName);
        $this->model = (class_exists($modelClassName))? new $modelClassName(): NULL;
    }

    public function getModel(){
        return $this->model;
    }

    public function modelLoaded(){
        return (!empty($this->model));
    }

    public function getViewVars(){
        return $this->viewVars;
    }

    public function getParams(){
        $sammy = Sammy::instance();
        $uri = explode('/',$sammy->uri);
        return (count($uri) > 2)? array_slice($uri, 3): NULL;

    }



} 