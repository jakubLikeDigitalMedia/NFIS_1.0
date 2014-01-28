<?php

class video_Controller extends App_Controller{

    public function index(){
        echo 'index action';
    }

    public function add() {
        // when creating a new db record
        echo 'add action';
    }

    public function create() {
        if(isset($_POST['video_code']) && !empty($_POST['video_code'])){
            $this-getModel()->insertVideo($_POST['video_code']);
            header('location: '.UNSECURE_URL.'/post/add/');
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

}
