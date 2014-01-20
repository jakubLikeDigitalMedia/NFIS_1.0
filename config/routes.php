<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 18/01/14
 * Time: 17:47
 */

//Router::get('/', 'welcome#people');

//Router::get('welcome', 'welcome#people');

Router::resource('employee');
Router::resource('group');