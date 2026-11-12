<?php

require_once __DIR__ . '/wp-config.php';

echo "DB_NAME: " . DB_NAME . "<br>";
echo "DB_USER: " . DB_USER . "<br>";
echo "DB_HOST: " . DB_HOST . "<br>";

$connection = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME
);

if ($connection->connect_error) {
    die("WP CONFIG CONNECTION FAILED: " . $connection->connect_error);
}

echo "WP CONFIG CONNECTION SUCCESS!";