<?php

// load all models in models directory
$models = scandir(MODELS);
include_once MODELS.'/model_abstract.php';

foreach ($models as $model) {
    if (in_array($model, array('.','..', 'model_abstract.php', 'model_interface.php')) ) continue;
    else{
        $modelPath = MODELS."/$model";
        if (file_exists($modelPath)) include_once $modelPath;
    }
}
