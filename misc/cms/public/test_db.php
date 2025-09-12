<?php

echo "Hello, world";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_DIR', dirname(__DIR__));

require BASE_DIR . '/config/db.php';

$db = new Database();
?>
