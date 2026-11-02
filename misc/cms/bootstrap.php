<?php

define('BASE_PATH', __DIR__);
define('BASE_DIR', dirname(__DIR__));

// Configure error logging first
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

error_log("=== BOOTSTRAP STARTED ===");
error_log("BASE_PATH: " . BASE_PATH);
error_log("PHP VERSION: " . PHP_VERSION);

// Start session
session_start();

// Composer autoloader
$autoload = __DIR__ . '/vendor/autoload.php';

if (!file_exists($autoload)) {
    error_log("ERROR: Composer autoload.php NOT FOUND");
    die("Composer autoload missing");
}

error_log("Composer autoload.php FOUND");

require_once $autoload;

error_log("Composer autoload loaded successfully");

// Database configuration
$dbConfig = __DIR__ . '/config/db.php';

if (!file_exists($dbConfig)) {
    error_log("ERROR: Database config NOT FOUND");
    die("Database configuration missing");
}

error_log("Database config FOUND");

require_once $dbConfig;

error_log("=== BOOTSTRAP COMPLETED ===");
