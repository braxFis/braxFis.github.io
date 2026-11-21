<?php

header('Content-Type: text/plain');

echo "PHP IS WORKING\n";
echo PHP_VERSION . "\n";

$autoload = __DIR__ . '/../vendor/autoload.php';

echo file_exists($autoload)
    ? "AUTOLOAD EXISTS\n"
    : "AUTOLOAD MISSING\n";
