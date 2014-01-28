<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 19/01/14
 * Time: 19:22
 */

//database connection info
//------------------------
if ($_SERVER['SERVER_NAME'] == 'nfis.com'){
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'admin');
    define('DB_DBASE', 'nobel_NFIS_DB');
}
else{
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_DBASE', 'nobel_NFIS_DB');

}