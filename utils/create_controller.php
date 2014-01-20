<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 19/01/14
 * Time: 12:49
 */

function createViews($views, $dir, $controller, $onlyIndex = TRUE){
    foreach($views as $view){
        echo "Creating view: $view at $dir/$view.html.php </br>";
        $file = $dir."/$view.html.php";
        if (file_put_contents($file, "view for $view action in $controller </br> path: $dir/$view.html.php", 0777)){
            chown($file, get_current_user());
            echo 'Done</br>';
        }
        else{
            echo 'Failed';
            $error = error_get_last();
            echo "<pre>Error: {$error['message']}</pre>";

        }
        if ($onlyIndex) break;
    }
}

define('CONTROLLERS_DIR', realpath('../app/controllers'));
define('VIEWS_DIR', realpath('../app/views'));

if (isset($_POST['controller_name']) && !empty($_POST['controller_name'])){
    $controllerName = filter_var($_POST['controller_name'], FILTER_SANITIZE_STRING);
    $controllerFile = CONTROLLERS_DIR.'/'.strtolower($controllerName).'_controller.php';
    $controllerViewDir = VIEWS_DIR.'/'.strtolower($controllerName);

    $onlyIndex = (isset($_POST['index']))? TRUE: FALSE;
    //die(var_dump($onlyIndex));

    $views = array(
        'index',
        'add',
        'create',
        'edit',
        'update',
        'destroy',
    );

    $controllerContent = <<<CODE
<?php

class {$controllerName}_Controller extends App_Controller{

    public function index(){
        echo 'index action';
    }

    public function add() {
        // when creating a new db record
        echo 'add action';
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

CODE;



    echo "Creating controller: {$controllerName}_Controller in path: $controllerFile </br>";
    if (file_put_contents($controllerFile, $controllerContent, 0766)){
        chown($controllerFile, get_current_user());
        echo 'Done</br>';
        echo "Creating views for {$controllerName}_Controller:</br>";
        if (!file_exists($controllerViewDir)){
            echo "Creating directory $controllerViewDir </br>";
            if (mkdir($controllerViewDir, 0766)){
                chown($controllerFile, get_current_user());
                echo 'Done';
                echo "Creatitng views</br>";
                createViews($views, $controllerViewDir, $controllerName, $onlyIndex);
            }
            else{
                echo "Failed";
                $error = error_get_last();
                echo "<pre>Error: {$error['message']}</pre>";
            }
        }
        else{
            echo "Directory $controllerViewDir exists allready</br>";
            echo "Creatitng views</br>";
            createViews($views, $controllerViewDir, $controllerName, $onlyIndex);

        }
    }
    else{
        echo "Failed";
        $error = error_get_last();
        echo "<pre>Error: {$error['message']}</pre>";
    }

}
else{?>
    <form method="post" action="<?= $_SERVER['PHP_SELF']?>">
        <label for="controller_name">Controller Name: </label><input type="text" size="40" id="controller_name" name="controller_name"></br>
        <label for="index">Create only index view: </label><input type="checkbox" id="index" name="index" value="1" checked="checked"></br>
        <input type="submit" name="submit" value="Create controller">
    </form>
<?php
}