<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Om fil finns (JS/CSS etc) → låt servern hantera
if (file_exists(__DIR__ . $uri)) {
  return false;
}

// Annars skicka allt till index.php
require_once __DIR__ . '/index.php';
