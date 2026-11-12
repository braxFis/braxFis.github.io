<?php

echo "CURRENT DIRECTORY: " . __DIR__ . "<br>";

echo "WP CONFIG EXISTS: ";
echo file_exists(__DIR__ . '/wp-config.php') ? 'YES' : 'NO';

echo "<br>WP LOAD EXISTS: ";
echo file_exists(__DIR__ . '/wp-load.php') ? 'YES' : 'NO';

echo "<br>WP SETTINGS EXISTS: ";
echo file_exists(__DIR__ . '/wp-settings.php') ? 'YES' : 'NO';