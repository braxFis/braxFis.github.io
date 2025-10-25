<?php
require_once __DIR__ . '/../bootstrap.php';
use app\widgets\PictureWidget;

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
$page = $_GET['page'] ?? 1;

if (!$id) {
    echo json_encode(['error' => 'Missing game ID']);
    exit;
}

$html = (new PictureWidget)::renderImageSideBar($id, $page);
echo json_encode(['html' => $html]);
