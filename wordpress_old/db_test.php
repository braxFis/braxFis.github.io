<?php

$host = 'localhost';
$user = 'root';
$password = 'pezDispenser1!';
$database = 'wordpress_db';
$port = 3306;

$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("ANSLUTNING MISSLYCKADES: " . $conn->connect_error);
}

echo "SUCCESS! PHP kan ansluta till MySQL och databasen.";