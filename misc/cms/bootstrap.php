<?php

//echo "BOOTSTRAP LOADED\n";
define('BASE_PATH', __DIR__);

session_start();

//set error reporting
ini_set('error_log', __DIR__ . '/php_error.log');
error_log("Test logg");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('BASE_DIR', dirname(__DIR__));

//Include Composer Autoloader
require_once __DIR__ . '/vendor/autoload.php';

//Include the database class
require_once 'config/db.php';