<?php

namespace app\controllers;

use app\models\CustomField;

class CustomFieldController
{
    public function index()
    {
        $fields = (new CustomField())->getAll();
        require __DIR__ . '/../views/customfields/index.php';
    }

public function save()
{
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    file_put_contents(__DIR__ . '/../../storage/debug.json', json_encode([
        'raw' => $json,
        'decoded' => $data
    ], JSON_PRETTY_PRINT));

    if (empty($data)) {
        echo json_encode(['success' => false, 'message' => 'No JSON received']);
        return;
    }

    if (!isset($data['fields']) || !is_array($data['fields'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }

    $model = new CustomField();
    $model->saveAll($data['fields']);

    echo json_encode(['success' => true]);
}

}
