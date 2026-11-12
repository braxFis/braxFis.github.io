<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('WP_USE_THEMES', false);

require_once __DIR__ . '/wp-load.php';

global $wpdb;

echo "<h3>WordPress databasdiagnostik</h3>";

echo "DB_HOST: " . htmlspecialchars(DB_HOST) . "<br>";
echo "DB_NAME: " . htmlspecialchars(DB_NAME) . "<br>";
echo "DB_USER: " . htmlspecialchars(DB_USER) . "<br>";

echo "<br>wpdb-objekt: ";
var_dump($wpdb);

echo "<br><br>Database error:<br>";
var_dump($wpdb->last_error);

echo "<br><br>MySQLi-anslutning:<br>";
var_dump($wpdb->dbh);

echo "<br><br>SELECT 1:<br>";
$result = $wpdb->get_var("SELECT 1");
var_dump($result);

echo "<br><br>Senaste databasfelet:<br>";
var_dump($wpdb->last_error);