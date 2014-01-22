<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 18/01/14
 * Time: 17:50
 */

class Router{

    public static $path = NULL;

    public static function resource($controller) {
        self::get('/' . $controller, $controller . '#index');
        self::get('/' . $controller . '/new', $controller . '#add');
        self::get('/' . $controller . '/edit/(.*)', $controller . '#edit');

        self::post('/' . $controller, $controller . '#create');
        self::put('/' . $controller, $controller . '#update');
        self::delete('/' . $controller, $controller . '#destroy');
    }

    public static function get($route, $path) {
        self::$path = $path;
        Sammy::process($route, 'GET');
    }

    public static function post($route, $path) {
        self::$path = $path;
        Sammy::process($route, 'POST');
    }

    public static function put($route, $path) {
        self::$path = $path;
        Sammy::process($route, 'PUT');
    }

    public static function delete($route, $path) {
        self::$path = $path;
        Sammy::process($route, 'DELETE');
    }

    public static function ajax($route, $path) {
        self::$path = $path;
        Sammy::process($route, 'XMLHttpRequest');
    }

    public static function preDispatch($uri) {
        //echo $uri;
        $path = explode('/', $uri);
        $controller = (empty($path[0])) ? 'index' : $path[0];
        $action = (empty($path[1])) ? 'index' : $path[1];
        $format = 'html';

        if( preg_match('/\.(\w+)$/', $action, $matches) ) {
            $action = str_replace($matches[0], '', $action);
            $format = $matches[1];
        }

        self::$path = $controller . '#' . $action;
        self::dispatch($format);

    }

    public static function dispatch($format){
        $path = explode('#', self::$path);
        $controller = $path[0];
        $action = $path[1];
        $content = NULL;

        try{
            // load app controller
            self::loadController('app');

            // load actual controller
            self::loadController($controller);

            // create class instance
            $classInstance = self::createClassInstance($controller);

            // call method
            if (is_callable(array($classInstance, $action))){
                try{
                    $classInstance->$action();
                }
                catch (DatabaseErrorException $e){
                    $errorLog = new ErrorLog($e);
                    $errorLog->createLog();
                    // redirect to error page
                    echo '<h1>Error</h1>';
                    echo "<p>{$e->getMessage()}</br>File: <pre>{$e->getFile()}</pre></br>Line: <pre>{$e->getLine()}</pre></p>";
                }
            }
            else{
                throw new SystemException("Method $action wasn't found in class ".get_class($classInstance));
            }

            // get view content
            $content = self::getViewContent($controller, $action, $format, $classInstance);
        }
        catch(SystemException $e){
            $errorLog = new ErrorLog($e);
            $errorLog->createLog();

            // redirect to error page
            echo '<h1>Error</h1>';
            echo "<p>{$e->getMessage()}</br>File: <pre>{$e->getFile()}</pre></br>Line: <pre>{$e->getLine()}</pre></p>";
        }

        // load layout
        $layoutPath = self::getLayout($controller, $action, $format, $classInstance);
        if (!empty($layoutPath)) include_once $layoutPath;

    }

    private static function loadController($controller){
        $controllerPath = CONTROLLERS.'/'.$controller.'_controller.php';
        if (file_exists($controllerPath)) include_once $controllerPath;
        else{
            throw new SystemException("Controller $controller wasn't found in $controllerPath");
        }

    }

    private static function createClassInstance($controller){
        $className = self::createClassName($controller);
        if (class_exists($className)){
            $classInstance = new $className;
            return $classInstance;
        }
        else{
            throw new SystemException("Class $className doesn't exists");
        }
    }

    private static function createClassName($controller){
        $parts = explode('_', $controller);
        $className = '';
        foreach ($parts as $part) { $className .= ucfirst($part).'_'; }
        $className .= 'Controller';
        return $className;
    }

    private static function loadView($controller, $action, $format, $classInstance){
        $controllerViewPath = VIEWS.'/'.$controller.'/'.$action.'.'.$format.'.php';
        //unset($controller, $action, $format);
        // get variables from controller

        foreach($classInstance->getViewVars() as $varName => $value){
            $$varName = $value;
        }

        if (file_exists($controllerViewPath)) include_once $controllerViewPath;
        else{
            throw new SystemException("View $action . $format.php wasn't found in $controllerViewPath");
        }
    }

    private static function getViewContent($controller, $action, $format, $classInstance){
        ob_start();
        self::loadView($controller, $action, $format, $classInstance);
        return ob_get_clean();
    }

    private static function getLayout($controller, $action, $format, $classInstance){

        // custom layout
        $customLayout = LAYOUTS.'/'.$classInstance->layout.'.'.$format.'.php';
        // controller-action.format.php
        $controllerActionPath = LAYOUTS.'/'.$controller.'-'.$action.'.'.$format.'.php';
        // controller.format.php
        $controllerPath = LAYOUTS.'/'.$controller.'.'.$format.'.php';
        // default application.format.php
        $applicationPath = LAYOUTS.'/application.'.$format.'.php';

        /*
        echo $controllerActionPath.'</br>';
        echo $controllerPath.'</br>';
        echo $applicationPath.'</br>';
        */

        $layoutPath = NULL;
        if (file_exists($customLayout)) $layoutPath = $customLayout;
        elseif (file_exists($controllerActionPath)) $layoutPath = $controllerActionPath;
        elseif (file_exists($controllerPath)) $layoutPath = $controllerPath;
        elseif (file_exists($applicationPath)) $layoutPath = $applicationPath;

        return $layoutPath;

    }
} 