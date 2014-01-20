<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 19/01/14
 * Time: 15:39
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('MODELS', realpath('../app/models'));

$models = scandir(MODELS);
unset($models[0], $models[1]);

foreach($models as $model){
    $file = MODELS."/$model";

    if (file_exists($file)){

        echo 'Process file '.$file.'</br>';

        $file = file_get_contents($file);
        //$defFile = file_get_contents($defFile);
        //$defFile = substr($defFile, strpos($defFile,'define'));

        preg_match('/class\s*(.*)\sextends\s*(.*){/', $file, $matches);
        //var_dump($matches);
        $newClassName = preg_replace('/([a-z])([A-Z])/', '$1_$2', $matches[1]);
        //die($newClassName);

        $file = str_replace($matches[1], $newClassName.'_Model', $file);
        $file = str_replace($matches[2], 'Model_Abstract', $file);

        $file = preg_replace("@define\(\s*'[A-Z]+_(.*)\s*',\s*('.*')\s*\)@", 'const $1 = $2', $file);
        $file = preg_replace("@[A-Z]*_(.*)\s*,\s*[A-Z]*_(.*)@", 'self::$1, self::$2', $file);
        file_put_contents(MODELS."/$model", $file);

    }
}