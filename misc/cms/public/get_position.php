<?php
if (file_exists('position.json')) {
    $data = file_get_contents('position.json');
    echo $data;
} else {
    echo json_encode(['dropzoneId' => null]);
}
?>
