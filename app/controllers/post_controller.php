<?php

class post_Controller extends App_Controller{

    public function index(){
        echo 'index action';
    }

    public function add() {
        $image = new Image_Model();
        $this->vars = array('imageActionLink' => $image->actionLink->getLink('create'));
        $dbc = new QueryHandler();
        $this->images = $dbc->selectQuery('SELECT * FROM ' . $image::TABLE, $image::PRM_KEY);
        
    }

    public function create() {
        // actually create the record
        echo 'create action';
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

}
