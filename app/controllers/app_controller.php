<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 18/01/14
 * Time: 19:06
 */

class App_Controller extends Controller{

    protected $queryHandler;

    public function __construct(){
        parent::__construct();
        $this->queryHandler = new QueryHandler();
    }

} 