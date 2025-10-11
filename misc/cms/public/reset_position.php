<?php
$file = 'position.json';
if (file_exists($file)) {
    unlink($file); // ta bort filen helt
}
echo json_encode(['status' => 'reset']);
